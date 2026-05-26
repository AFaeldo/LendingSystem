@extends('layouts.app')

@section('title', 'Add Borrower')
@section('page-title', 'Add Borrower')

@section('content')

<div class="max-w-2xl mx-auto">
    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Borrower Details</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('borrowers.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <x-form-input name="firstname" label="First Name" placeholder="e.g., John" value="{{ old('firstname') }}" />
                        <x-field-error field="firstname" />
                    </div>

                    <div>
                        <x-form-input name="lastname" label="Last Name" placeholder="e.g., Doe" value="{{ old('lastname') }}" />
                        <x-field-error field="lastname" />
                    </div>

                    <div>
                        <x-form-input name="middlename" label="Middle Name" placeholder="e.g., Smith" value="{{ old('middlename') }}" />
                        <x-field-error field="middlename" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sex</label>
                        <select name="gender" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition {{ $errors->has('gender') ? 'border-red-500' : '' }}">
                            <option value="">Select sex</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <x-field-error field="gender" />
                    </div>

                    <div>
                        <x-form-input type="number" name="age" label="Age" placeholder="e.g., 25" min="0" max="150" value="{{ old('age') }}" />
                        <x-field-error field="age" />
                    </div>

                    <div>
                        <x-form-input name="purok" label="Purok / Sitio" placeholder="e.g., Purok 1" value="{{ old('purok') }}" />
                        <x-field-error field="purok" />
                    </div>

                    <div class="md:col-span-2">
                        <x-form-input name="contact" label="Contact Number" placeholder="e.g., 09123456789" value="{{ old('contact') }}" />
                        <x-field-error field="contact" />
                    </div>

                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <x-button type="submit" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                        Save Borrower
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
