@extends('layouts.app')

@section('title', 'Edit Item')
@section('page-title', 'Edit Item')

@section('content')

<div class="max-w-2xl mx-auto">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Item Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ITEM CODE (AUTO-GENERATED STATE) --}}
                    <div>
                        <x-form-input name="item_code" label="Item Code" :value="$item->item_code" readonly class="bg-slate-50 text-slate-400 cursor-not-allowed" />
                    </div>

                    {{-- TOTAL STOCK QUANTITY --}}
                    <div>
                        <x-form-input name="quantity" label="Quantity" type="number" :value="$item->quantity" />
                    </div>

                    {{-- ITEM NAME --}}
                    <div class="md:col-span-2">
                        <x-form-input name="name" label="Item Name" :value="$item->name" />
                    </div>

                    {{-- CATEGORY DIRECT SELECTOR --}}
                    <div class="md:col-span-2">
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

                    {{-- ITEM CONDITION STATE SELECTOR --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Item Condition</label>
                        <select name="condition" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">-- Select Condition --</option>
                            <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }}>✨ Good</option>
                            <option value="Fair" {{ $item->condition == 'Fair' ? 'selected' : '' }}>👍 Fair</option>
                            <option value="Poor" {{ $item->condition == 'Poor' ? 'selected' : '' }}>⚠️ Poor</option>
                            <option value="Damaged" {{ $item->condition == 'Damaged' ? 'selected' : '' }}>❌ Damaged</option>
                        </select>
                    </div>

                </div>

                {{-- FORM INTERACTIONS FOOTER --}}
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
