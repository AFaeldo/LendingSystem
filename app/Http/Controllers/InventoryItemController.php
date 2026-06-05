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
        // 🔥 FIX: Ginawang latest() o id DESC para ang pinakahuling idinagdag ay laging nasa unahan (No. 1)
        $items = InventoryItem::with('category')
            ->where('status', '!=', 0)
            ->latest('id')
            ->paginate(15);

        return view('items.index', compact('items'));
    }

    /**
     * Display a listing of archived inventory items.
     */
    public function archiveIndex()
    {
        // 🔥 FIX: Ginawang pinakabago rin ang unahan para sa archives registry list
        $archivedItems = InventoryItem::with('category')
            ->where('status', 0)
            ->latest('id')
            ->get();

        return view('items.archive', compact('archivedItems'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        // Auto-calculate sequential pattern tracking structure (e.g., EQ-2026-001)
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
        // 1. I-validate muna ang request inputs mula sa form
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

        // 🔥 FIX: Hahanapin kung may umiiral nang item na kapareho ang Pangalan at Kondisyon (at hindi naka-archive)
        $existingItem = InventoryItem::where('name', $validatedData['name'])
            ->where('condition', $validatedData['condition'])
            ->where('status', '!=', 0)
            ->first();

        if ($existingItem) {
            // KUNG MERON NANG KAPAREHO: I-add lang ang bagong quantity sa lumang data row
            $existingItem->quantity += $validatedData['quantity'];
            $existingItem->available += $validatedData['quantity'];

            // Kung may bagong larawang in-upload, palitan ang lumang file dependency
            if ($request->hasFile('image')) {
                if ($existingItem->image_path && Storage::disk('public')->exists($existingItem->image_path)) {
                    Storage::disk('public')->delete($existingItem->image_path);
                }
                $existingItem->image_path = $request->file('image')->store('items', 'public');
            }

            // Opsyonal: I-update ang kategorya o deskripsyon kung binago ito ng secretary
            if ($request->filled('category_id')) {
                $existingItem->category_id = $validatedData['category_id'];
            }
            if ($request->filled('description')) {
                $existingItem->description = $validatedData['description'];
            }

            $existingItem->save();

            return redirect()->route('items.index')->with('success', 'Item quantity updated successfully inside the existing record.');
        }

        // KUNG WALA PANG KAPAREHO: Dito pa lang mag-ge-generate ng bagong sequential Item Code
        $latestItem = InventoryItem::latest('id')->first();
        $nextId = $latestItem ? $latestItem->id + 1 : 1;
        $generatedCode = 'EQ-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // I-compile ang data array para sa bagong item row insertion entry
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

        // Dynamically adjust inventory stock calculations safely
        $lentCount = $item->quantity - $item->available;
        $data['available'] = $data['quantity'] - $lentCount;

        // Clean up older image structures if a brand new file gets uploaded
        if ($request->hasFile('image')) {
            if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }

    /**
     * Archive the specified inventory item record.
     */
    public function archive(InventoryItem $item)
    {
        // 0 = Archived Status Flag Value Context
        $item->update(['status' => 0]);
        return redirect()->route('items.index')->with('success', 'Item profile archived successfully');
    }

    /**
     * Restore an archived inventory record back to live registries.
     */
    public function restore(InventoryItem $item)
    {
        // 1 = Active Status Flag Value Context
        $item->update(['status' => 1]);
        return redirect()->route('items.archive')->with('success', 'Item restored to active inventory successfully');
    }

    /**
     * Remove the specified item from storage permanently.
     */
    public function destroy(InventoryItem $item)
    {
        // Clean up tracking file dependencies on storage disk before deletion
        if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();
        return back()->with('success', 'Item removed permanently');
    }
}
