@extends('layouts.app')

@section('title', 'Report Analysis')
@section('page-title', 'Report Analysis')

@section('content')

<div class="max-w-5xl mx-auto mt-2 space-y-6">

    {{-- TOP ACTION ACTION BAR --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-emerald-600 transition">
                <i class="ti ti-arrow-left"></i> Back to All Reports
            </a>
            <h1 class="text-2xl font-bold text-slate-800 mt-1">Compiled System Log</h1>
        </div>
        <button onclick="window.print()" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-50 transition shadow-sm cursor-pointer">
            <i class="ti ti-printer text-sm"></i> Print / Save PDF
        </button>
    </div>

    {{-- METADATA OVERVIEW CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
        <div class="border-b md:border-b-0 md:border-r border-slate-100 pb-3 md:pb-0 md:pr-4">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Scope Target</span>
            <span class="inline-block mt-2 px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                {{ ucfirst($report->type) }}
            </span>
        </div>
        <div class="border-b md:border-b-0 md:border-r border-slate-100 py-3 md:py-0 md:px-4">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Operator Log</span>
            <span class="text-sm font-bold text-slate-800 block mt-1.5">
                @if(($report->generator->name ?? 'System') === 'System')
                    <span class="text-slate-500 italic">🤖 Automated Run</span>
                @else
                    {{ $report->generator->name }}
                @endif
            </span>
        </div>
        <div class="border-b md:border-b-0 md:border-r border-slate-100 py-3 md:py-0 md:px-4">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Snapshot Date</span>
            <span class="text-sm font-semibold text-slate-700 block mt-1.5">
                {{ optional($report->generated_at)->format('M d, Y • h:i A') }}
            </span>
        </div>
        <div class="pt-3 md:pt-0 md:pl-4">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Volume Checked</span>
            <span class="text-sm font-mono font-bold text-emerald-600 block mt-1.5">
                {{ number_format($report->total_records) }} Active Row(s)
            </span>
        </div>
    </div>

    {{-- SCOPE CONTEXT REMARKS --}}
    @if($report->meta)
        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 text-xs text-slate-600 leading-relaxed">
            <strong class="text-slate-800 block mb-1">Compilation Note:</strong>
            {{ $report->meta }}
        </div>
    @endif

    {{-- ORGANIZED REPORT RESULTS DATA CONTAINER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/40">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                <i class="ti ti-list-details text-emerald-500"></i> Detailed Data Ledger
            </h3>
        </div>

        <div class="overflow-x-auto">
            @if($reportData->isEmpty())
                <div class="p-12 text-center text-slate-400 text-sm">
                    <i class="ti ti-database-off text-3xl block text-slate-300 mb-2"></i>
                    No specific sub-records matched during this execution frame.
                </div>
            @else
                <table class="w-full text-sm">
                    {{-- CONDITIONAL HEADERS BASED ON TYPE --}}
                    @if(in_array($report->type, ['lendings', 'overdue']))
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-left text-slate-500 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Borrower</th>
                                <th class="px-6 py-3.5">Assigned Asset</th>
                                <th class="px-6 py-3.5">Due Date</th>
                                <th class="px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reportData as $trx)
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">{{ $trx->borrower->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-3.5">{{ $trx->item->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3.5 font-mono text-xs">{{ \Carbon\Carbon::parse($trx->due_at)->format('M d, Y') }}</td>
                                    <td class="px-6 py-3.5">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase {{ $trx->status === 'active' ? 'bg-amber-50 border border-amber-200 text-amber-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
                                            {{ $trx->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    @elseif($report->type === 'returns')
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-left text-slate-500 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Returned By</th>
                                <th class="px-6 py-3.5">Asset Restored</th>
                                <th class="px-6 py-3.5">Return Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reportData as $ret)
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">{{ $ret->lendingTransaction->borrower->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-3.5">{{ $ret->lendingTransaction->item->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-3.5 font-mono text-xs">{{ $ret->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    @elseif($report->type === 'borrowers')
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-left text-slate-500 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Full Name</th>
                                <th class="px-6 py-3.5">Contact Detail</th>
                                <th class="px-6 py-3.5">Registered Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reportData as $borrower)
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">{{ $borrower->name }}</td>
                                    <td class="px-6 py-3.5 font-mono text-xs text-slate-600">{{ $borrower->email ?? $borrower->phone ?? 'N/A' }}</td>
                                    <td class="px-6 py-3.5 text-slate-500">{{ $borrower->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    @else
                        {{-- DEFAULT INVENTORY ITEMS RENDERING --}}
                        <thead class="bg-slate-50/80 border-b border-slate-100 text-left text-slate-500 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Asset Code / Name</th>
                                <th class="px-6 py-3.5 text-center">Remaining Available Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($reportData as $item)
                                <tr class="hover:bg-slate-50/40 transition">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">{{ $item->name }}</td>
                                    <td class="px-6 py-3.5 text-center font-mono font-bold text-slate-800">{{ number_format($item->available) }} unit(s)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            @endif
        </div>
    </div>
</div>

@endsection
