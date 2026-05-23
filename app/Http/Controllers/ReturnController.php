<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnTransaction;
use App\Models\LendingTransaction;
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
            'quantity' => 'required|integer|min:1',
            'condition' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $lending = LendingTransaction::findOrFail($data['lending_transaction_id']);
        $item = $lending->item;

        if ($data['quantity'] > $lending->quantity) {
            return back()->withErrors(['quantity' => 'Quantity cannot exceed borrowed amount'])->withInput();
        }

        $item->increment('available', $data['quantity']);

        $data['returned_at'] = now()->toDateString();
        $data['processed_by'] = auth()->id();
        $return = ReturnTransaction::create($data);

        if ($data['quantity'] >= $lending->quantity) {
            $lending->update(['status' => 'returned']);
        }

        return redirect()->route('returns.index')->with('success','Return processed');
    }

    public function show(ReturnTransaction $return)
    {
        $return->load('lending.borrower','lending.item');
        return view('returns.show', compact('return'));
    }
}
