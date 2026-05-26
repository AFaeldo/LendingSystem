@extends('layouts.app')

@section('title', 'Lending Details')
@section('page-title', 'Lending Details')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-0">
    {{-- HEADER PAGE --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-xs text-slate-400 mt-1 font-mono">Transaction ID: #{{ $lending->id }}</p>
        </div>

        {{-- DYNAMIC BADGE STATUS --}}
        <div class="self-start sm:self-auto">
            <span class="px-3 py-1 text-xs font-semibold rounded-full border shadow-sm inline-block
                {{ $lending->status === 'overdue' ? 'bg-red-50 border-red-100 text-red-700' : '' }}
                {{ $lending->status === 'returned' ? 'bg-green-50 border-green-100 text-green-700' : '' }}
                {{ $lending->status === 'active' ? 'bg-amber-50 border-amber-100 text-amber-700' : '' }}">
                ● {{ ucfirst($lending->status) }}
            </span>
        </div>
    </div>

    {{-- MAIN DETAIL CARD --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 space-y-8">

            {{-- GRID FOR MAIN INFORMATION --}}
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2">

                {{-- BORROWER ROW --}}
                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                        <i class="ti ti-user text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Borrower</span>
                        <p class="mt-0.5 font-bold text-slate-800 text-sm sm:text-base">
                            {{ $lending->borrower->firstname }} {{ $lending->borrower->lastname }}
                        </p>
                    </div>
                </div>

                {{-- ITEM ROW --}}
                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                        <i class="ti ti-package text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Item Name</span>
                        <p class="mt-0.5 font-bold text-slate-800 text-sm sm:text-base">{{ $lending->item->name }}</p>
                    </div>
                </div>

                {{-- QUANTITY ROW --}}
                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg shrink-0">
                        <i class="ti ti-hash text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Quantity Borrowed</span>
                        <p class="mt-0.5 font-bold text-slate-800 text-sm sm:text-base font-mono">{{ $lending->quantity }} pc/s</p>
                    </div>
                </div>

                {{-- TIME COUNTER OR CONDITION PLACEHOLDER ROW --}}
                <div class="flex items-start gap-3 bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                    <div class="p-2 bg-slate-100 text-slate-600 rounded-lg shrink-0">
                        <i class="ti ti-info-circle text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Condition Context</span>
                        <p class="mt-0.5 font-bold text-slate-700 text-sm sm:text-base">{{ $lending->item->condition ?? 'Good' }}</p>
                    </div>
                </div>

            </div>

            <hr class="border-slate-100" />

            {{-- TIMESTAMPS PERIOD SCHEDULER GRID --}}
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
                <div class="bg-slate-50 rounded-xl p-4 flex flex-col justify-between border border-slate-100">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Date Borrowed</span>
                    <div class="flex items-center gap-2 text-slate-700">
                        <i class="ti ti-calendar-event text-lg text-emerald-500"></i>
                        <span class="font-semibold text-sm sm:text-base">{{ optional($lending->borrowed_at)->format('F d, Y') }}</span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 flex flex-col justify-between border border-slate-100">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Expected Due Date</span>
                    <div class="flex items-center gap-2 text-slate-700">
                        <i class="ti ti-calendar-time text-lg {{ $lending->status === 'overdue' ? 'text-red-500' : 'text-blue-500' }}"></i>
                        <span class="font-semibold text-sm sm:text-base">{{ optional($lending->due_at)->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- RESPONSIVE BUTTON ACTION ACTIONS PANEL --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('lendings.index') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition cursor-pointer order-2 sm:order-1">
                    <i class="ti ti-arrow-left text-base"></i>
                    Back to Registry
                </a>

                {{-- Itatago ang 'Process Return' button kung naibalik na ang item --}}
                @if($lending->status !== 'returned')
                    <a href="{{ route('returns.create', ['lending_id' => $lending->id]) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow order-1 sm:order-2 cursor-pointer">
                        <i class="ti ti-checkbox text-base"></i>
                        Process Return
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection
