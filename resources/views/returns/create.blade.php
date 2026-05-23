@extends('layouts.app')

@section('title', 'New Return')
@section('page-title', 'New Return')

@section('content')

<div class="max-w-2xl mx-auto">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Return Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('returns.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Active Lending</label>
                        <select name="lending_transaction_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select an outstanding lending record</option>
                            @foreach($lendings as $lending)
                                <option value="{{ $lending->id }}">
                                    {{ $lending->borrower->lastname }}, {{ $lending->borrower->firstname }} — {{ $lending->item->name }} ({{ $lending->quantity }} borrowed)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-form-input name="quantity" label="Quantity Returned" type="number" />
                    </div>

                    <div>
                        <x-form-input name="condition" label="Condition" placeholder="e.g., Good, Damaged, Shiny" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="remarks" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition p-3 h-28 placeholder:text-gray-400" placeholder="Enter remarks or incident observations if any..."></textarea>
                    </div>

                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                        Save Return
                    </x-button>

                    <a href="{{ route('returns.index') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </x-card>
</div>

@endsection
