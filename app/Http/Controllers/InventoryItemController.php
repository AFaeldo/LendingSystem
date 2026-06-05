<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryItem;
use App\Models\Category;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the active items.
     */
    public function index(Request $request)
    {
        // FIX: Inayos ang pagkakasunod-sunod (mula EQ-2026-001 pataas)
        $items = InventoryItem::with('category')
            ->where('status', '!=', 0)
            ->orderBy('item_code', 'asc')
            ->paginate(15);

        return view('items.index', compact('items'));
    }

    /**
     * Display a listing of archived inventory items.
     */
    public function archiveIndex()
    {
        $archivedItems = InventoryItem::with('category')
            ->where('status', 0)
            ->orderBy('item_code', 'asc')
            ->get();

        return view('items.archive', compact('archivedItems'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $latestItem = InventoryItem::latest('id')->first();
        $nextId = $latestItem ? $latestItem->id + 1 : 1;
        $nextItemCode = 'EQ-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('items.create', compact('categories', 'nextItemCode'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0|max:100000',
            'condition' => 'required|string|in:Good,Fair',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Item name is required',
            'name.min' => 'Item name must be at least 2 characters',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity cannot be negative',
            'quantity.max' => 'Quantity cannot exceed 100,000',
            'condition.required' => 'Item condition is required',
            'condition.in' => 'Hindi pwedeng magpasok ng sirang gamit (Poor/Damaged) sa bagong imbentaryo.',
            'image.image' => 'The file must be an image',
            'image.max' => 'Image size cannot exceed 2MB',
        ]);

        // FIX: Hanapin kung may active item na kapareho ang Name at Condition
        $existingItem = InventoryItem::where('name', $validatedData['name'])
            ->where('condition', $validatedData['condition'])
            ->where('status', '!=', 0)
            ->first();

        if ($existingItem) {
            // I-increment lang ang quantity ng umiiral na record
            $existingItem->quantity += $validatedData['quantity'];
            $existingItem->available += $validatedData['quantity'];

            if ($request->hasFile('image')) {
                if ($existingItem->image_path && Storage::disk('public')->exists($existingItem->image_path)) {
                    Storage::disk('public')->delete($existingItem->image_path);
                }
                $existingItem->image_path = $request->file('image')->store('items', 'public');
            }

            if ($request->filled('category_id')) {
                $existingItem->category_id = $validatedData['category_id'];
            }
            if ($request->filled('description')) {
                $existingItem->description = $validatedData['description'];
            }

            $existingItem->save();
            return redirect()->route('items.index')->with('success', 'Item quantity updated successfully inside the existing record.');
        }

        // Kung walang duplicate, gumawa ng bagong row at bagong sequential item_code
        $latestItem = InventoryItem::latest('id')->first();
        $nextId = $latestItem ? $latestItem->id + 1 : 1;
        $generatedCode = 'EQ-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $data = $validatedData;
        $data['item_code'] = $generatedCode;
        $data['available'] = $data['quantity'];
        $data['status'] = 1;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $data['image_path'] = $path;
        }

        InventoryItem::create($data);
        return redirect()->route('items.index')->with('success', 'Item added successfully');
    }

    /**
     * SHOW THE EDIT FORM
     */
    public function edit(InventoryItem $item)
    {
        $categories = Category::orderBy('name')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, InventoryItem $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0|max:100000',
            'condition' => 'required|string|in:Good,Fair',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Item name is required',
            'name.min' => 'Item name must be at least 2 characters',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity cannot be negative',
            'quantity.max' => 'Quantity cannot exceed 100,000',
            'condition.required' => 'Item condition is required',
            'condition.in' => 'Maaari lamang i-update ang kondisyon bilang Good o Fair.',
            'image.image' => 'The file must be an image',
            'image.max' => 'Image size cannot exceed 2MB',
        ]);

        $lentCount = $item->quantity - $item->available;
        $data['available'] = $data['quantity'] - $lentCount;

        if ($request->hasFile('image')) {
            if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }

    public function archive(InventoryItem $item)
    {
        $item->update(['status' => 0]);
        return redirect()->route('items.index')->with('success', 'Item profile archived successfully');
    }

    public function restore(InventoryItem $item)
    {
        $item->update(['status' => 1]);
        return redirect()->route('items.archive')->with('success', 'Item restored to active inventory successfully');
    }

    public function destroy(InventoryItem $item)
    {
        if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();
        return back()->with('success', 'Item removed permanently');
    }
}
