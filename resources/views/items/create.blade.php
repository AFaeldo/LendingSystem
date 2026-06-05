@extends('layouts.app')

@section('title', 'Add Item')
@section('page-title', 'Add Item')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
        <a href="{{ route('items.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
            <i class="ti ti-arrow-left text-lg"></i>
            Back to Record
        </a>
    </div>

    <x-success-alert />
    <x-error-alert />

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="font-bold text-slate-800 text-lg">Item Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Item Code</label>
                        <div class="relative">
                            <input type="text" name="item_code" readonly class="w-full text-sm rounded-xl border-gray-200 bg-slate-50 text-slate-500 cursor-not-allowed font-mono px-4 py-2.5" value="{{ $nextItemCode ?? 'Auto-generating...' }}" />
                            <span class="absolute right-3 top-2.5 text-xs font-semibold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Auto</span>
                        </div>
                    </div>

                    <div>
                        <x-form-input name="quantity" label="Quantity" type="number" placeholder="0" min="0" value="{{ old('quantity', 0) }}" />
                        <x-field-error field="quantity" />
                    </div>

                    <div class="md:col-span-2">
                        <x-form-input id="item_name" name="name" label="Item Name" placeholder="e.g., Extension Cord, Projector, Monobloc Chair" value="{{ old('name') }}" />
                        <x-field-error field="name" />
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-semibold text-slate-700">Category</label>
                            <button type="button" onclick="openCategoryModal()" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition cursor-pointer">
                                <i class="ti ti-plus"></i> Add New Category
                            </button>
                        </div>
                        <select id="category_select" name="category_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition h-[42px] {{ $errors->has('category_id') ? 'border-red-500' : '' }}">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <p class="text-[11px] text-slate-400 mt-1.5 ml-1 leading-relaxed">💡 <span class="font-medium text-slate-500">Smart Tip:</span> Kung wala sa listahan, i-click ang Add New Category.</p>

                        <x-field-error field="category_id" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Item Condition</label>
                        <select name="condition" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition h-[42px] {{ $errors->has('condition') ? 'border-red-500' : '' }}">
                            <option value="">-- Select Condition --</option>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                        <x-field-error field="condition" />
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center min-w-[100px]">
                        Cancel
                    </a>

                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer min-w-[120px]">
                        Save Item
                    </x-button>
                </div>
            </form>
        </div>
    </x-card>
</div>

<div id="categoryModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-opacity">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden transform scale-95 transition-transform duration-200">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Add New Category</h3>
            <button type="button" onclick="closeCategoryModal()" class="text-slate-400 hover:text-slate-600 transition text-lg cursor-pointer">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name</label>
                <input type="text" id="new_category_name" placeholder="e.g., Electronics, Kitchenware"
                    class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition px-4 py-2.5" />
                <p id="category_error" class="hidden text-xs text-red-500 mt-1.5 font-medium"></p>
            </div>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
            <button type="button" onclick="closeCategoryModal()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-xs font-semibold transition min-w-[80px] cursor-pointer">
                Cancel
            </button>
            <button type="button" onclick="submitInlineCategory()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-semibold transition min-w-[100px] shadow-xs cursor-pointer">
                Save Category
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemNameInput = document.getElementById('item_name');
        const categorySelect = document.getElementById('category_select');

        if (itemNameInput && categorySelect) {
            itemNameInput.addEventListener('input', function() {
                const name = this.value.toLowerCase().trim();
                if (!name) return;

                let keywordGroup = "";

                if (/chair|table|desk|stool|whiteboard|bench|furniture/i.test(name)) {
                    keywordGroup = "furniture";
                } else if (/cord|cable|projector|extension|charger|mouse|keyboard|monitor|hdmi|tv|speaker|sound|microphone|wire/i.test(name)) {
                    keywordGroup = "electronic";
                } else if (/hammer|screwdriver|drill|pliers|wrench|tape|saw|ladder|mower/i.test(name)) {
                    keywordGroup = "tool";
                } else if (/pen|paper|notebook|pencil|stapler|marker|folder/i.test(name)) {
                    keywordGroup = "stationery";
                } else if (/tent|canopy|tolda|stage|lights|sound system/i.test(name)) {
                    keywordGroup = "event";
                } else if (/wheelchair|stretcher|oxygen|first aid|bp/i.test(name)) {
                    keywordGroup = "medical";
                }

                if (keywordGroup !== "") {
                    for (let i = 0; i < categorySelect.options.length; i++) {
                        const optionText = categorySelect.options[i].text.toLowerCase();
                        if (optionText.includes(keywordGroup)) {
                            categorySelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            });
        }
    });

    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
        document.getElementById('new_category_name').focus();
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('new_category_name').value = '';
        document.getElementById('category_error').classList.add('hidden');
    }

    function submitInlineCategory() {
        const nameInput = document.getElementById('new_category_name');
        const errorText = document.getElementById('category_error');
        const name = nameInput.value.trim();

        if (!name) {
            errorText.innerText = "The category name field is required.";
            errorText.classList.remove('hidden');
            return;
        }

        fetch("/categories", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('category_select');
                const newOption = new Option(data.category.name, data.category.id, true, true);
                select.add(newOption);
                closeCategoryModal();
            } else {
                errorText.innerText = data.message || "Failed to create category.";
                errorText.classList.remove('hidden');
            }
        })
        .catch(err => {
            errorText.innerText = "An error occurred. Please ensure the category route is open.";
            errorText.classList.remove('hidden');
        });
    }
</script>

@endsection
