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
            <form action="{{ route('returns.store') }}" method="POST" class="space-y-5" id="returnForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ACTIVE LENDING RECORDS SELECTION --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Active Lending</label>
                        <select name="lending_transaction_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select an outstanding lending record</option>
                            @foreach($lendings as $lending)
                                <option value="{{ $lending->id }}" {{ old('lending_transaction_id') == $lending->id ? 'selected' : '' }}>
                                    {{ $lending->borrower->lastname }}, {{ $lending->borrower->firstname }} — {{ $lending->item->name }} ({{ $lending->quantity }} borrowed)
                                </option>
                            @endforeach
                        </select>
                        @error('lending_transaction_id')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- QUANTITY RETURNED INPUT --}}
                    <div>
                        <x-form-input name="quantity" label="Quantity Returned" type="number" min="1" value="{{ old('quantity', 1) }}" />
                        @error('quantity')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONDITION SELECT DROPDOWN --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Condition</label>
                        <select name="condition" id="conditionSelect" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition">
                            <option value="">Select condition</option>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                            <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                        @error('condition')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DYNAMIC PENALTY AMOUNT FIELD (Lalabas kapag Damaged/Poor) --}}
                    <div id="penaltyField" class="hidden md:col-span-2 bg-rose-50/50 border border-rose-100 rounded-xl p-4 transition-all duration-300">
                        <label class="block text-sm font-semibold text-rose-800 mb-1.5">Assessment Penalty Fee (₱)</label>
                        <input type="number" name="penalty_amount" placeholder="0.00" step="0.01" min="0" value="{{ old('penalty_amount') }}"
                            class="w-full text-sm rounded-xl border-rose-200 focus:border-rose-400 focus:ring focus:ring-rose-100 text-slate-700 transition" />
                        <p class="text-[11px] text-rose-600 mt-1">Leave blank or input 0 if valuation fee is to be determined later by management.</p>
                    </div>

                    {{-- REMARKS TEXTAREA --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="remarks" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700 transition p-3 h-28 placeholder:text-gray-400" placeholder="Enter remarks or damage incident details here...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- BORROWER LIABILITY & DAMAGE AGREEMENT BOX --}}
                <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 my-2">
                    <h4 class="text-xs font-bold text-amber-900 flex items-center gap-1.5 mb-1.5">
                        <i class="ti ti-alert-triangle text-sm"></i> Account Liability & Consequence Agreement
                    </h4>
                    <p class="text-[11px] text-amber-800 leading-relaxed mb-3">
                        By signing/saving this form, the system confirms that the object must be returned intact. If recorded under
                        <strong>Poor or Damaged</strong> status, the borrower's privileges will be locked (<span class="font-semibold text-red-600">INACTIVE Status</span>)
                        and they are legally bound to pay the required repair cost or replacement valuation fee before reactivation.
                    </p>

                    <label class="relative flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="damage_agreement" value="1" required
                            class="w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:ring-emerald-500 focus:ring-2 transition">
                        <span class="text-xs font-semibold text-slate-700">
                            I confirm the borrower accepts the accountability settlement terms.
                        </span>
                    </label>
                    @error('damage_agreement')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FORM ACTIONS BUTTON --}}
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

{{-- SCRIPT PARA SA AUTOMATIC SHOW/HIDE NG MULTA BOX --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const conditionSelect = document.getElementById('conditionSelect');
        const penaltyField = document.getElementById('penaltyField');

        function togglePenaltyField() {
            const val = conditionSelect.value;
            if (val === 'Poor' || val === 'Damaged') {
                penaltyField.classList.remove('hidden');
            } else {
                penaltyField.classList.add('hidden');
            }
        }

        conditionSelect.addEventListener('change', togglePenaltyField);
        togglePenaltyField(); // Run initial check if page has validation errors
    });
</script>
@endsection
