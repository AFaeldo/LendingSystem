@extends('layouts.app')

@section('title', 'Edit Item')
@section('page-title', 'Edit Item')

@section('content')

<div class="max-w-2xl mx-auto">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Item Details</h2>
            <p class="text-xs text-slate-500 mt-0.5">Modify the information records for this inventory asset</p>
        </div>

        <div class="p-6">
            <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <x-form-input name="item_code" label="Item Code" :value="$item->item_code" />

                <x-form-input name="name" label="Name" :value="$item->name" />

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-form-input name="quantity" label="Quantity" type="number" :value="$item->quantity" />

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Item Image</label>

                    <div class="flex items-start gap-4">
                        @if($item->image_path)
                            <div class="relative group shrink-0">
                                <img src="{{ asset('storage/' . $item->image_path) }}" class="h-20 w-20 object-cover rounded-xl border border-slate-100 shadow-sm" />
                                <div class="absolute inset-0 bg-black/10 rounded-xl pointer-events-none"></div>
                            </div>
                        @endif

                        <div class="w-full">
                            <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:transition file:cursor-pointer cursor-pointer border border-gray-200 rounded-xl p-1" />
                            <p class="text-[11px] text-gray-400 mt-1.5">Accepted formats: PNG, JPG, JPEG. Max size: 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                        Save Changes
                    </x-button>

                    <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </x-card>
</div>

@endsection
