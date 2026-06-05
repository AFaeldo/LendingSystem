@extends('layouts.app')

@section('title', 'New Lending')
@section('page-title', 'New Lending')

@section('content')
<div class="max-w-2xl mx-auto">
    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Transaction Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('lendings.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Borrower</label>
                        <select name="borrower_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select borrower</option>
                            @foreach($borrowers as $borrower)
                                <option value="{{ $borrower->id }}" {{ old('borrower_id') == $borrower->id ? 'selected' : '' }}>
                                    {{ $borrower->lastname }}, {{ $borrower->firstname }} — (Purok: {{ $borrower->purok ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('borrower_id')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Item</label>
                        <select name="inventory_item_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select inventory item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_code }} - {{ $item->name }} [Condition: {{ $item->condition }}] ({{ $item->available }} available)
                                </option>
                            @endforeach
                        </select>
                        @error('inventory_item_id')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 sm:col-span-1">
                        <x-form-input name="quantity" label="Quantity" type="number" min="1" value="{{ old('quantity', 1) }}" />
                        @error('quantity')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <div class="bg-blue-50 border border-blue-100 text-blue-800 p-4 rounded-xl flex items-start gap-3">
                            <div class="text-blue-500 mt-0.5">
                                <i class="ti ti-info-circle text-xl"></i>
                            </div>
                            <div class="text-sm">
                                <span class="font-bold block mb-0.5">Automated 7-Day Lending Period</span>
                                Ang Date Borrowed ay awtomatikong itatala sa araw na ito. Ang borrower ay bibigyan ng eksaktong <strong>7 araw na palugit</strong> para maisoli ang hiniram na gamit.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                        Save Lending
                    </x-button>
                    <a href="{{ route('lendings.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </x-card>
</div>
@endsection
