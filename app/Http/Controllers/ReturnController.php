<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnTransaction;
use App\Models\LendingTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryItem;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReturnController extends Controller
{
    /**
     * Ipakita ang listahan ng lahat ng binalik na gamit.
     */
    public function index()
    {
        $returns = ReturnTransaction::with(['lending.borrower','lending.item'])
            ->orderBy('returned_at','desc')
            ->paginate(15);

        return view('returns.index', compact('returns'));
    }

    /**
     * 🔥 EXPORT TO CSV ENGINE
     * Pinapagana ang automated na pag-download ng spreadsheet logs para sa mga admin.
     */
    public function export()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // I-set ang mga haligi (Columns) ng export document
            fputcsv($handle, [
                'Lend ID',
                'Borrower Name',
                'Item Name',
                'Quantity',
                'Due Date',
                'Return Date',
                'Condition',
                'Payment Status',
                'Penalty Amount'
            ]);

            // Gumamit ng Chunking (bawat 100 piraso) para hindi mahirapan o mag-crash ang memory ng server
            ReturnTransaction::with(['lending.borrower', 'lending.item'])
                ->orderBy('returned_at', 'desc')
                ->chunk(100, function ($returns) use ($handle) {
                    foreach ($returns as $return) {

                        $borrower = $return->lending?->borrower
                            ? $return->lending->borrower->firstname . ' ' . $return->lending->borrower->lastname
                            : 'Unknown Borrower';

                        $itemName = $return->lending?->item?->name ?? 'N/A (Deleted Item)';

                        $dueDate = $return->lending?->due_at
                            ? \Carbon\Carbon::parse($return->lending->due_at)->format('Y-m-d')
                            : 'N/A';

                        $returnDate = $return->returned_at
                            ? \Carbon\Carbon::parse($return->returned_at)->format('Y-m-d')
                            : 'N/A';

                        fputcsv($handle, [
                            '#' . ($return->lending_transaction_id ?? $return->lending?->id),
                            $borrower,
                            $itemName,
                            $return->quantity,
                            $dueDate,
                            $returnDate,
                            $return->condition ?? 'Returned',
                            $return->payment_status ?? 'N/A',
                            number_format($return->penalty_amount ?? 0, 2)
                        ]);
                    }
                });

            fclose($handle);
        });

        // Set mandatory HTTP attachment headers kung saan kusa nitong pipilitin ang browser na i-download ang file
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Returns_Report_' . now()->format('Y-m-d_H-i') . '.csv"');

        return $response;
    }

    /**
     * Ipakita ang form para sa paggawa ng bagong return transaction.
     */
    public function create()
    {
        $lendings = LendingTransaction::with(['borrower','item'])
            ->whereIn('status', ['active', 'overdue'])
            ->orderBy('due_at')
            ->get();

        return view('returns.create', compact('lendings'));
    }

    /**
     * I-save ang bagong binalik na gamit at i-update ang estado ng imbentaryo at hiram.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'lending_transaction_id' => 'required|exists:lending_transactions,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'condition' => 'nullable|in:Good,Fair,Poor,Damaged',
            'remarks' => 'nullable|string|max:1000',
            'damage_agreement' => 'required|accepted',
            'penalty_amount' => 'nullable|numeric|min:0',
        ], [
            'lending_transaction_id.required' => 'Please select a lending transaction',
            'lending_transaction_id.exists' => 'Selected transaction is invalid',
            'quantity.required' => 'Return quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Return quantity must be at least 1',
            'quantity.max' => 'Return quantity cannot exceed 1000',
            'remarks.max' => 'Remarks cannot exceed 1000 characters',
            'damage_agreement.required' => 'You must check the agreement box to proceed.',
        ]);

        $lending = LendingTransaction::findOrFail($data['lending_transaction_id']);

        if (!in_array($lending->status, ['active', 'overdue'])) {
            return back()->withErrors(['lending_transaction_id' => 'This transaction has already been fully returned.'])->withInput();
        }

        if ($data['quantity'] > $lending->quantity) {
            return back()->withErrors(['quantity' => "Quantity cannot exceed currently borrowed amount ({$lending->quantity})"])->withInput();
        }

        // Ibalik ang pormal na bilang ng stock sa Inventory module
        $item = $lending->item;
        if ($item) {
            $item->increment('available', $data['quantity']);
        }

        // CONSEQUENCE CONTROL LOGIC
        $isDamaged = in_array($data['condition'], ['Poor', 'Damaged']);
        $borrower = $lending->borrower;

        // Pag-setup sa mga system metadata logs
        $data['returned_at'] = now();
        $data['processed_by'] = Auth::id();
        $data['payment_status'] = $isDamaged ? 'Pending' : 'N/A';
        $data['penalty_amount'] = $isDamaged ? ($request->input('penalty_amount') ?? 0.00) : 0.00;

        // Itala ang Return Transaction sa Database
        $returnTransaction = ReturnTransaction::create($data);

        // Awtomatikong i-suspend (Inactive) ang Borrower kapag may sira ang gamit base sa patakaran
        if ($isDamaged && $borrower) {
            $borrower->update(['status' => 'inactive']);
        }

        // KONTROL SA QUANTITY AT STATUS MANAGEMENT
        if ($data['quantity'] == $lending->quantity) {
            $lending->update([
                'quantity' => 0,
                'status' => 'returned'
            ]);
        } else {
            $lending->decrement('quantity', $data['quantity']);
        }

        // Pagpapalit ng flash messages depende sa kondisyon ng isinauling aytem
        if ($isDamaged) {
            return redirect()->route('returns.index')->with('error', "Return processed with DAMAGE. Borrower account status has been suspended (INACTIVE) under the agreement rules.");
        }

        return redirect()->route('returns.index')->with('success', 'Return processed successfully in acceptable condition.');
    }

    /**
     * Ipakita ang buong detalye ng isang partikular na rekord ng pagbabalik.
     */
    public function show(ReturnTransaction $return)
    {
        $return->load('lending.borrower','lending.item');
        return view('returns.show', compact('return'));
    }
}
