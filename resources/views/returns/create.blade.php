@extends('layouts.app')

@section('title', 'New Return')
@section('page-title', 'New Return')

@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">New Return</h1>
        <p class="text-sm text-gray-500 mt-1">Process a returned lending transaction</p>
    </div>

    <x-card>
        <div class="p-6 border-b">
            <h2 class="font-bold text-gray-800">Return Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('returns.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Active Lending</label>
                    <select name="lending_transaction_id" class="form-input">
                        <option value="">Select a lending</option>
                        @foreach($lendings as $lending)
                            <option value="{{ $lending->id }}">
                                {{ $lending->borrower->lastname }}, {{ $lending->borrower->firstname }} — {{ $lending->item->name }} ({{ $lending->quantity }} borrowed)
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-form-input name="quantity" label="Quantity Returned" type="number" />
                <x-form-input name="condition" label="Condition" />

                <div>
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-input h-28" placeholder="Enter remarks if any"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" class="btn-primary">Save Return</x-button>
                    <a href="{{ route('returns.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
