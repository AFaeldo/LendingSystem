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
        // Tinatanggal ang mga archived items (0) sa pangunahing listahan
        $items = InventoryItem::with('category')
            ->where('status', '!=', 0)
            ->orderBy('name')
            ->paginate(15);

        return view('items.index', compact('items'));
    }

    /**
     * Display a listing of archived inventory items.
     */
    public function archiveIndex()
    {
        // Kinukuha lamang ang mga marked archived values (0) mula sa vault scope
        $archivedItems = InventoryItem::with('category')
            ->where('status', 0)
            ->orderBy('name')
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
        // Force server-side generation to prevent user bypass or tampering with readonly input
        $latestItem = InventoryItem::latest('id')->first();
        $nextId = $latestItem ? $latestItem->id + 1 : 1;
        $generatedCode = 'EQ-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $request->merge(['item_code' => $generatedCode]);

        $data = $request->validate([
            'item_code' => 'required|string|unique:inventory_items,item_code|max:100|regex:/^[A-Z0-9\-]+$/',
            'name' => 'required|string|max:255|min:2',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0|max:100000',
            'condition' => 'nullable|in:Good,Fair,Poor,Damaged',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'item_code.required' => 'Item code is required',
            'item_code.unique' => 'This item code already exists',
            'item_code.regex' => 'Item code must contain only uppercase letters, numbers, and hyphens',
            'name.required' => 'Item name is required',
            'name.min' => 'Item name must be at least 2 characters',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity cannot be negative',
            'quantity.max' => 'Quantity cannot exceed 100,000',
            'image.image' => 'The file must be an image',
            'image.max' => 'Image size cannot exceed 2MB',
        ]);

        $data['available'] = $data['quantity'];

        // RESOLUTION: Ginawang integer structure tracking identifier flag (1 = Active)
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
            'condition' => 'nullable|in:Good,Fair,Poor,Damaged',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Item name is required',
            'name.min' => 'Item name must be at least 2 characters',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity cannot be negative',
            'quantity.max' => 'Quantity cannot exceed 100,000',
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
