<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:2|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Category name is required',
            'name.unique' => 'Category name already exists',
            'name.min' => 'Category name must be at least 2 characters',
            'slug.unique' => 'Category slug already exists',
            'description.max' => 'Description cannot exceed 1000 characters',
        ]);

        // Awtomatikong pagbuo ng slug kapag iniwang walang laman
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = Category::create($data);

        // FIX FOR MODAL ERROR: Kung AJAX o JSON request ang tumawag, magre-return ng JSON Response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 200);
        }

        // Standard redirect kung galing sa tradisyunal na page creation form
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:2|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Category name is required',
            'name.unique' => 'Category name already exists',
            'name.min' => 'Category name must be at least 2 characters',
            'slug.unique' => 'Category slug already exists',
            'description.max' => 'Description cannot exceed 1000 characters',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Pigilan ang pagbura kung may mga items na nakatali sa kategoryang ito
        if ($category->items()->exists()) {
            return back()->withErrors(['category' => 'Cannot delete category with items']);
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }
}

