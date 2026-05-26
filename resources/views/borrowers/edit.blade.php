@extends('layouts.app')

@section('title', 'Edit Borrower')
@section('page-title', 'Edit Borrower')

@section('content')

<div class="max-w-2xl mx-auto">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="font-bold text-slate-800 text-lg">Edit Borrower Profile</h2>
            </div>

            {{-- Visual Badge representing Current Status --}}
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                {{ $borrower->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                {{ $borrower->status === 'inactive' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}
                {{ $borrower->status === 'suspended' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                {{ $borrower->status === 'archived' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
            ">
                {{ $borrower->status }}
            </span>
        </div>

        <div class="p-6">
            <form action="{{ route('borrowers.update', $borrower) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- FIRST NAME --}}
                    <div>
                        <x-form-input name="firstname" label="First Name" :value="old('firstname', $borrower->firstname)" placeholder="e.g., John" />
                        <x-field-error field="firstname" />
                    </div>

                    {{-- LAST NAME --}}
                    <div>
                        <x-form-input name="lastname" label="Last Name" :value="old('lastname', $borrower->lastname)" placeholder="e.g., Doe" />
                        <x-field-error field="lastname" />
                    </div>

                    {{-- MIDDLE NAME --}}
                    <div>
                        <x-form-input name="middlename" label="Middle Name" :value="old('middlename', $borrower->middlename)" placeholder="e.g., Smith" />
                        <x-field-error field="middlename" />
                    </div>

                    {{-- SEX DROPDOWN --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sex</label>
                        <select name="gender" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition h-[42px] {{ $errors->has('gender') ? 'border-red-500' : '' }}">
                            <option value="">Select sex</option>
                            <option value="Male" {{ old('gender', $borrower->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $borrower->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <x-field-error field="gender" />
                    </div>

                    {{-- AGE --}}
                    <div>
                        <x-form-input type="number" name="age" label="Age" :value="old('age', $borrower->age)" placeholder="e.g., 25" min="0" max="150" />
                        <x-field-error field="age" />
                    </div>

                    {{-- PUROK / SITIO --}}
                    <div>
                        <x-form-input name="purok" label="Purok / Sitio" :value="old('purok', $borrower->purok)" placeholder="e.g., Purok 1" />
                        <x-field-error field="purok" />
                    </div>

                    {{-- CONTACT NUMBER --}}
                    <div>
                        <x-form-input name="contact" label="Contact Number" :value="old('contact', $borrower->contact)" placeholder="e.g., 09123456789" />
                        <x-field-error field="contact" />
                    </div>

                    {{-- STATUS MANAGEMENT DROPDOWN --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Account Status</label>
                        <select name="status" class="w-full text-sm rounded-xl font-medium border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition h-[42px] {{ $errors->has('status') ? 'border-red-500' : '' }}">
                            <option value="active" {{ old('status', $borrower->status) == 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="inactive" {{ old('status', $borrower->status) == 'inactive' ? 'selected' : '' }}>⚫ Inactive</option>
                            <option value="suspended" {{ old('status', $borrower->status) == 'suspended' ? 'selected' : '' }}>🔴 Suspended</option>
                            <option value="archived" {{ old('status', $borrower->status) == 'archived' ? 'selected' : '' }}>🟡 Archived</option>
                        </select>
                        <x-field-error field="status" />
                    </div>

                </div>

                {{-- SUBMIT BUTTON LAYOUT PANEL --}}
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <a href="{{ url('/borrowers') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition text-center min-w-[100px]">
                        Cancel
                    </a>

                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer min-w-[120px]">
                        Save Changes
                    </x-button>
                </div>
            </form>
        </div>
    </x-card>
</div>

@endsection
