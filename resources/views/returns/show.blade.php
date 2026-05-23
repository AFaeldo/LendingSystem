@extends('layouts.app')

@section('title', 'Return Details')
@section('page-title', 'Return Details')

@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Return Details</h1>
        <p class="text-sm text-gray-500 mt-1">Review the processed return transaction</p>
    </div>

    <x-card>
        <div class="p-6 space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <span class="text-sm text-slate-500">Borrower</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $return->lending->borrower->firstname }} {{ $return->lending->borrower->lastname }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">Item</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $return->lending->item->name }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">Quantity Returned</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $return->quantity }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">Returned At</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ optional($return->returned_at)->format('F d, Y') }}</p>
                </div>
            </div>

            <div>
                <span class="text-sm text-slate-500">Condition</span>
                <p class="mt-1 text-gray-900">{{ $return->condition ?? 'Not provided' }}</p>
            </div>

            <div>
                <span class="text-sm text-slate-500">Remarks</span>
                <p class="mt-1 text-gray-900">{{ $return->remarks ?? 'No remarks' }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('returns.index') }}" class="btn">Back to returns</a>
            </div>
        </div>
    </x-card>
</div>

@endsection
