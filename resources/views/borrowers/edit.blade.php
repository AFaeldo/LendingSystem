@extends('layouts.app')

@section('title', 'Edit Borrower')
@section('page-title', 'Edit Borrower')

@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Edit Borrower</h1>
        <p class="text-sm text-gray-500 mt-1">Update borrower details</p>
    </div>

    <x-card>
        <div class="p-6 border-b">
            <h2 class="font-bold text-gray-800">Borrower Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('borrowers.update', $borrower) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form-input name="firstname" label="Firstname" :value="$borrower->firstname" />
                    <x-form-input name="lastname" label="Lastname" :value="$borrower->lastname" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input">
                            <option value="">Select gender</option>
                            <option value="Male" {{ $borrower->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $borrower->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $borrower->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <x-form-input name="purok" label="Purok" :value="$borrower->purok" />
                </div>

                <x-form-input name="middlename" label="Middlename" :value="$borrower->middlename" />
                <x-form-input name="address" label="Address" :value="$borrower->address" />
                <x-form-input name="organization" label="Organization" :value="$borrower->organization" />
                <x-form-input name="contact" label="Contact" :value="$borrower->contact" />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" class="btn-primary">Save Changes</x-button>

                    <a href="{{ url('/borrowers') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
