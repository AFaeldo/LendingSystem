<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LendingTransaction;
use App\Models\InventoryItem;
use App\Models\Borrower;

class LendingController extends Controller
{
    public function index()
    {
        $lendings = LendingTransaction::with(['borrower','item'])->orderBy('borrowed_at','desc')->paginate(15);
        return view('lendings.index', compact('lendings'));
    }

    public function create()
    {
        $borrowers = Borrower::orderBy('lastname')->get();
        $items = InventoryItem::where('available','>',0)->orderBy('name')->get();
        return view('lendings.create', compact('borrowers','items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'due_at' => 'required|date',
        ]);

        $item = InventoryItem::findOrFail($data['inventory_item_id']);
        if ($item->available < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient available stock'])->withInput();
        }

        $item->decrement('available', $data['quantity']);
        $data['borrowed_at'] = now()->toDateString();
        $data['status'] = 'active';
        LendingTransaction::create($data + ['processed_by' => auth()->id()]);

        return redirect()->route('lendings.index')->with('success','Lending recorded');
    }

    public function show(LendingTransaction $lending)
    {
        return view('lendings.show', compact('lending'));
    }
}
