@extends('layouts.app')

@section('title', 'Return Details')
@section('page-title', 'Return Details')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-0">

    <x-card class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Return Details</h2>
                <p class="text-xs text-slate-500 mt-0.5">Transaction review for an item returned to inventory</p>
            </div>

            <span class="px-2.5 py-1 text-xs font-semibold rounded-full border bg-green-50 border-green-100 text-green-700">
                Processed
            </span>
        </div>

        <div class="p-6 space-y-6">

            {{-- ACCOUNTABILITY PENALTY NOTICE BANNER --}}
            @if(in_array($return->condition, ['Poor', 'Damaged']))
                <div class="p-4 rounded-xl border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3
                    {{ $return->payment_status === 'Paid' ? 'bg-green-50 border-green-100 text-green-800' : 'bg-red-50 border-red-100 text-red-800' }}">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider">Accountability Invoice</h4>
                        <p class="text-lg font-mono font-bold mt-1">Penalty Fee: ₱{{ number_format($return->penalty_amount, 2) }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-lg font-mono text-xs font-bold border uppercase
                        {{ $return->payment_status === 'Paid' ? 'bg-white border-green-200 text-green-700' : 'bg-white border-red-200 text-red-700 animate-pulse' }}">
                        Payment: {{ $return->payment_status }}
                    </span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-100/80">
                {{-- BORROWER ROW --}}
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Borrower</span>
                    <p class="mt-1.5 font-semibold text-slate-800 text-base">
                        @if($return->lending?->borrower)
                            {{ $return->lending->borrower->firstname }} {{ $return->lending->borrower->lastname }}
                        @else
                            <span class="text-slate-400 italic font-normal text-sm">Unknown Borrower (Record Missing)</span>
                        @endif
                    </p>
                </div>

                {{-- RETURNED ITEM ROW --}}
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Returned Item</span>
                    <p class="mt-1.5 font-semibold text-slate-800 text-base">
                        {{ $return->lending?->item?->name ?? 'N/A (Deleted Item)' }}
                    </p>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Quantity Returned</span>
                    <p class="mt-1.5 font-mono font-bold text-slate-800 text-base">
                        {{ $return->quantity }} pc/s
                    </p>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Returned At</span>
                    <p class="mt-1.5 font-medium text-slate-700 text-sm sm:text-base">
                        {{ optional($return->returned_at)->format('F d, Y') ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Item Condition</span>
                    @if($return->condition)
                        <p class="text-sm font-semibold rounded-xl p-3 border
                            {{ in_array($return->condition, ['Poor', 'Damaged']) ? 'bg-red-50/50 border-red-100 text-red-700' : 'bg-slate-50 border-slate-100/60 text-slate-700' }}">
                            {{ $return->condition }}
                        </p>
                    @else
                        <span class="inline-block text-xs text-slate-400 italic bg-slate-50/50 border border-slate-100 px-3 py-1.5 rounded-lg">
                            No condition report provided
                        </span>
                    @endif
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Remarks</span>
                    @if($return->remarks)
                        <p class="text-sm text-slate-700 bg-slate-50 border border-slate-100/60 rounded-xl p-3 leading-relaxed">
                            {{ $return->remarks }}
                        </p>
                    @else
                        <span class="inline-block text-xs text-slate-400 italic bg-slate-50/50 border border-slate-100 px-3 py-1.5 rounded-lg">
                            No processing remarks written
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center pt-4 border-t border-slate-50">
                <a href="{{ route('returns.index') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition w-full sm:w-auto">
                    <i class="ti ti-chevron-left text-sm"></i>
                    Back to returns
                </a>
            </div>
        </div>

    </x-card>
</div>

@endsection
