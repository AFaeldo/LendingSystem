<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LendingTransaction;
use App\Models\InventoryItem;
use App\Models\Borrower;
use Carbon\Carbon;

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
        // Siguraduhing may stock pa ang mga ipapakitang gamit sa dropdown selection list
        $items = InventoryItem::where('available', '>', 0)->orderBy('name')->get();
        return view('lendings.create', compact('borrowers','items'));
    }

    public function store(Request $request)
    {
        // Tinanggal ang due_at sa user input validation para hindi ito ma-manipula ng user
        $data = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1|max:1000',
        ], [
            'borrower_id.required' => 'Please select a borrower',
            'borrower_id.exists' => 'Selected borrower is invalid',
            'inventory_item_id.required' => 'Please select an item',
            'inventory_item_id.exists' => 'Selected item is invalid',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Quantity cannot exceed 1000',
        ]);

        $item = InventoryItem::findOrFail($data['inventory_item_id']);

        // PATAKARAN: Hindi pwedeng humiram kung wala nang natitirang stock o kulang ang stock
        if ($item->available <= 0) {
            return back()->withErrors(['inventory_item_id' => 'This item is currently out of stock and cannot be borrowed.'])->withInput();
        }

        if ($item->available < $data['quantity']) {
            return back()->withErrors(['quantity' => "Out of stock! Only {$item->available} item(s) available."])->withInput();
        }

        // Check kung may hawak pa silang active na ganitong item ngayon
        $existingLending = LendingTransaction::where('borrower_id', $data['borrower_id'])
            ->where('inventory_item_id', $data['inventory_item_id'])
            ->where('status', 'active')
            ->first();

        if ($existingLending) {
            return back()->withErrors(['inventory_item_id' => 'This borrower already has an active lending record for this item'])->withInput();
        }

        // BUKAS NA PAG-PROCESO: Auto-calculation ng Oras at 7-Araw na palugit
        $today = Carbon::now();

        $data['borrowed_at'] = $today->toDateString(); // Kung kailan pinroseso ngayon
        $data['due_at']      = $today->addDays(7)->toDateString(); // Auto-set sa eksaktong 7 araw na palugit
        $data['status']      = 'active';
        $data['processed_by'] = Auth::id();

        // Bawasan ang available stocks ng item
        $item->decrement('available', $data['quantity']);

        LendingTransaction::create($data);
        return redirect()->route('lendings.index')->with('success', 'Lending transaction recorded successfully with a 7-day return period.');
    }

    public function show(LendingTransaction $lending)
    {
        return view('lendings.show', compact('lending'));
    }
}
