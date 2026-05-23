@extends('layouts.app')

@section('title', 'New Lending')
@section('page-title', 'New Lending')

@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">New Lending</h1>
        <p class="text-sm text-gray-500 mt-1">Record a new barangay lending transaction</p>
    </div>

    <x-card>
        <div class="p-6 border-b">
            <h2 class="font-bold text-gray-800">Transaction Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('lendings.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Borrower</label>
                    <select name="borrower_id" class="form-input">
                        <option value="">Select borrower</option>
                        @foreach($borrowers as $borrower)
                            <option value="{{ $borrower->id }}">{{ $borrower->lastname }}, {{ $borrower->firstname }} - {{ $borrower->purok }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Item</label>
                    <select name="inventory_item_id" class="form-input">
                        <option value="">Select inventory item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_code }} - {{ $item->name }} ({{ $item->available }} available)</option>
                        @endforeach
                    </select>
                </div>

                <x-form-input name="quantity" label="Quantity" type="number" />

                <div>
                    <label class="form-label">Due date</label>
                    <input type="date" name="due_at" class="form-input" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" class="btn-primary">Save Lending</x-button>
                    <a href="{{ route('lendings.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
