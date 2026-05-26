<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnTransaction;
use App\Models\LendingTransaction;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryItem;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnTransaction::with(['lending.borrower','lending.item'])->orderBy('returned_at','desc')->paginate(15);
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $lendings = LendingTransaction::with(['borrower','item'])
            ->where('status', 'active')
            ->orderBy('due_at')
            ->get();

        return view('returns.create', compact('lendings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lending_transaction_id' => 'required|exists:lending_transactions,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'condition' => 'nullable|in:Good,Fair,Poor,Damaged',
            'remarks' => 'nullable|string|max:1000',
        ], [
            'lending_transaction_id.required' => 'Please select a lending transaction',
            'lending_transaction_id.exists' => 'Selected transaction is invalid',
            'quantity.required' => 'Return quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Return quantity must be at least 1',
            'quantity.max' => 'Return quantity cannot exceed 1000',
            'remarks.max' => 'Remarks cannot exceed 1000 characters',
        ]);

        $lending = LendingTransaction::findOrFail($data['lending_transaction_id']);

        if ($lending->status !== 'active') {
            return back()->withErrors(['lending_transaction_id' => 'Only active lendings can be returned'])->withInput();
        }

        if ($data['quantity'] > $lending->quantity) {
            return back()->withErrors(['quantity' => "Quantity cannot exceed borrowed amount ({$lending->quantity})"])->withInput();
        }

        $item = $lending->item;
        $item->increment('available', $data['quantity']);

        $data['returned_at'] = now()->toDateString();
        $data['processed_by'] = Auth::id();
        $return = ReturnTransaction::create($data);

        if ($data['quantity'] >= $lending->quantity) {
            $lending->update(['status' => 'returned']);
        }

        return redirect()->route('returns.index')->with('success', 'Return processed successfully');
    }

    public function show(ReturnTransaction $return)
    {
        $return->load('lending.borrower','lending.item');
        return view('returns.show', compact('return'));
    }
}
