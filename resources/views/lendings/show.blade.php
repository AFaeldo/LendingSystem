@extends('layouts.app')

@section('title', 'Lending Details')
@section('page-title', 'Lending Details')

@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Lending Details</h1>
        <p class="text-sm text-gray-500 mt-1">Review the lending transaction</p>
    </div>

    <x-card>
        <div class="p-6 space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <span class="text-sm text-slate-500">Borrower</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $lending->borrower->firstname }} {{ $lending->borrower->lastname }}</p>
                </div>

                <div>
                    <span class="text-sm text-slate-500">Item</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $lending->item->name }}</p>
                </div>

                <div>
                    <span class="text-sm text-slate-500">Quantity</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ $lending->quantity }}</p>
                </div>

                <div>
                    <span class="text-sm text-slate-500">Status</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ ucfirst($lending->status) }}</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <span class="text-sm text-slate-500">Borrowed At</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ optional($lending->borrowed_at)->format('F d, Y') }}</p>
                </div>

                <div>
                    <span class="text-sm text-slate-500">Due Date</span>
                    <p class="mt-1 font-semibold text-gray-900">{{ optional($lending->due_at)->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('lendings.index') }}" class="btn">Back to list</a>
                <a href="{{ route('returns.create') }}" class="btn btn-secondary">Process Return</a>
            </div>
        </div>
    </x-card>
</div>

@endsection
