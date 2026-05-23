<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Category;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $items = InventoryItem::with('category')->orderBy('name')->paginate(15);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_code' => 'required|string|unique:inventory_items,item_code',
            'name' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:0',
        ]);
        $data['available'] = $data['quantity'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $data['image_path'] = $path;
        }

        InventoryItem::create($data);
        return redirect()->route('items.index')->with('success','Item added');
    }

    public function edit(InventoryItem $item)
    {
        $categories = Category::orderBy('name')->get();
        return view('items.edit', compact('item','categories'));
    }

    public function update(Request $request, InventoryItem $item)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:0',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $data['image_path'] = $path;
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success','Item updated');
    }

    public function destroy(InventoryItem $item)
    {
        $item->delete();
        return back()->with('success','Item removed');
    }
}
