@extends('layouts.app')

@section('title', 'Edit Borrower')
@section('page-title', 'Edit Borrower')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800">Edit Borrower</h1>
        <p class="text-sm text-slate-500 mt-1">Update borrower details</p>
    </div>

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Borrower Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('borrowers.update', $borrower) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <x-form-input name="firstname" label="First Name" :value="$borrower->firstname" placeholder="e.g., John" />
                    </div>

                    <div>
                        <x-form-input name="lastname" label="Last Name" :value="$borrower->lastname" placeholder="e.g., Doe" />
                    </div>

                    <div>
                        <x-form-input name="middlename" label="Middle Name" :value="$borrower->middlename" placeholder="e.g., Smith" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gender</label>
                        <select name="gender" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select gender</option>
                            <option value="Male" {{ $borrower->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $borrower->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $borrower->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <x-form-input name="purok" label="Purok / Sitio" :value="$borrower->purok" placeholder="e.g., Purok 1" />
                    </div>

                    <div>
                        <x-form-input name="contact" label="Contact Number" :value="$borrower->contact" placeholder="e.g., 09123456789" />
                    </div>

                    <div class="md:col-span-2">
                        <x-form-input name="organization" label="Organization / Affiliation" :value="$borrower->organization" placeholder="e.g., Local Youth Council" />
                    </div>

                    <div class="md:col-span-2">
                        <x-form-input name="address" label="Complete Address" :value="$borrower->address" placeholder="e.g., Street Name, Barangay, City" />
                    </div>

                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                        Save Changes
                    </x-button>

                    <a href="{{ url('/borrowers') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
