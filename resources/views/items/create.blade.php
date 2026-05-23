@extends('layouts.app')

@section('title', 'Add Item')
@section('page-title', 'Add Item')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Add Inventory Item</h1>
        <p class="text-sm text-gray-500 mt-1">Create a new inventory record</p>
    </div>

    <x-card>
        <div class="p-6 border-b">
            <h2 class="font-bold text-gray-800">Item Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <x-form-input name="item_code" label="Item Code" />
                <x-form-input name="name" label="Name" />

                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input">
                        <option value="">-- Select --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <x-form-input name="quantity" label="Quantity" type="number" />

                <div>
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-input" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" class="btn-primary">Save</x-button>
                    <a href="{{ route('items.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
