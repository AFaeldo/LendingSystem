@extends('layouts.app')

@section('title', 'Return Details')
@section('page-title', 'Return Details')

@section('content')

<div class="max-w-2xl mx-auto">

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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-100/80">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Borrower</span>
                    <p class="mt-1.5 font-semibold text-slate-800 text-base">
                        {{ $return->lending->borrower->firstname }} {{ $return->lending->borrower->lastname }}
                    </p>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Returned Item</span>
                    <p class="mt-1.5 font-semibold text-slate-800 text-base">
                        {{ $return->lending->item->name }}
                    </p>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Quantity Returned</span>
                    <p class="mt-1.5 font-mono font-bold text-slate-800 text-base">
                        {{ $return->quantity }}
                    </p>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Returned At</span>
                    <p class="mt-1.5 font-medium text-slate-700">
                        {{ optional($return->returned_at)->format('F d, Y — h:i A') ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Item Condition</span>
                    @if($return->condition)
                        <p class="text-sm text-slate-700 bg-slate-50 border border-slate-100/60 rounded-xl p-3">
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
                <a href="{{ route('returns.index') }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition">
                    <i class="ti ti-chevron-left text-sm"></i>
                    Back to returns
                </a>
            </div>
        </div>

    </x-card>
</div>

@endsection
