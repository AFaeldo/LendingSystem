@extends('layouts.app')

@section('title', 'Add Borrower')
@section('page-title', 'Add Borrower')

@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Add Borrower</h1>
        <p class="text-sm text-gray-500 mt-1">Create a new borrower record</p>
    </div>

    <x-card>
        <div class="p-6 border-b">
            <h2 class="font-bold text-gray-800">Borrower Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('borrowers.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form-input name="firstname" label="Firstname" />
                    <x-form-input name="lastname" label="Lastname" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <x-form-input name="purok" label="Purok" />
                </div>

                <x-form-input name="middlename" label="Middlename" />
                <x-form-input name="address" label="Address" />
                <x-form-input name="organization" label="Organization" />
                <x-form-input name="contact" label="Contact" />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" class="btn-primary">Save</x-button>

                    <a href="{{ url('/borrowers') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection

