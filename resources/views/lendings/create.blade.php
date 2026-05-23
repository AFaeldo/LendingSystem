@extends('layouts.app')

@section('title', 'New Lending')
@section('page-title', 'New Lending')

@section('content')

<div class="max-w-2xl mx-auto">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Transaction Details</h2>
            <p class="text-xs text-slate-500 mt-0.5">Record a new equipment or item lending transaction</p>
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
                                <option value="{{ $borrower->id }}">
                                    {{ $borrower->lastname }}, {{ $borrower->firstname }} — ({{ $borrower->purok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Item</label>
                        <select name="inventory_item_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select inventory item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->item_code }} - {{ $item->name }} ({{ $item->available }} available)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-form-input name="quantity" label="Quantity" type="number" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Due Date</label>
                        <input type="date" name="due_at" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-600 transition" />
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
