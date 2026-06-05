@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')

{{-- AUTOMATED LEDGER INFORMATION HEADER --}}
<div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-5 text-slate-700 mt-2">
    <div class="flex items-center gap-1.5 text-slate-800 font-bold text-sm">
        <i class="ti ti-cpu text-emerald-500 text-base"></i> Automated Ledger Active
    </div>
    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
        The system automatically evaluates, balances, and logs lending transactions and borrower activity every night at midnight (<strong class="text-slate-700">23:59</strong>). Manual generation is disabled.
    </p>
</div>

{{-- 📊 STATS SUMMARY CARDS ROW --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">

    {{-- Card 1: Total Compiled Logs --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Total Ledger Runs</span>
            <span class="text-2xl font-black text-slate-800 block mt-1 font-mono">
                {{ number_format($stats['total_logs'] ?? 0) }}
            </span>
            <span class="text-[11px] text-slate-500 block mt-0.5">Historical system snapshots</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 shadow-inner">
            <i class="ti ti-database text-xl"></i>
        </div>
    </div>

    {{-- Card 2: Modules Breakdown --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Distribution Scope</span>
            <div class="flex items-center gap-3 mt-2">
                <div class="text-xs font-semibold text-slate-700">
                    <span class="font-mono font-bold text-emerald-600 block text-sm">{{ $stats['lending_runs'] ?? 0 }}</span> Lendings
                </div>
                <div class="w-px h-6 bg-slate-200"></div>
                <div class="text-xs font-semibold text-slate-700">
                    <span class="font-mono font-bold text-brand-600 block text-sm">{{ $stats['borrower_runs'] ?? 0 }}</span> Borrowers
                </div>
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50/50 border border-emerald-100/60 flex items-center justify-center text-emerald-600 shadow-inner">
            <i class="ti ti-chart-pie text-xl"></i>
        </div>
    </div>

    {{-- Card 3: Last Sync Timestamp --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Last Compilation Run</span>
            <span class="text-sm font-bold text-slate-800 block mt-2">
                {{ $stats['last_run'] ? $stats['last_run']->format('M d, Y • h:i A') : 'No logs recorded' }}
            </span>
            <span class="text-[10px] font-mono text-emerald-600 block mt-0.5">
                {{ $stats['last_run'] ? $stats['last_run']->diffForHumans() : 'Standby state' }}
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-50/50 border border-brand-100/60 flex items-center justify-center text-brand-600 shadow-inner">
            <i class="ti ti-clock-bolt text-xl"></i>
        </div>
    </div>

</div>

{{-- MAIN REPORTS TABLE CARD --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mt-4">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-bold text-slate-800">System Generated Reports</h2>
        </div>

        <button onclick="window.print()" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100/70 cursor-pointer">
            <i class="ti ti-printer text-base"></i>
            Print Logs Summary
        </button>
    </div>

    {{-- DESKTOP VIEW TABLE --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4">Report Type</th>
                    <th class="px-6 py-4">Source Engine</th>
                    <th class="px-6 py-4">Data Volume</th>
                    <th class="px-6 py-4">Date Logs</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 border border-slate-200 text-slate-700">
                                {{ ucfirst($report->type) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-medium text-slate-800">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                <i class="ti ti-settings text-[10px]"></i> System Auto
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                            {{ number_format($report->total_records) }} rows evaluated
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ optional($report->generated_at)->format('M d, Y') }}
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                View Full Data <i class="ti ti-chevron-right text-xs"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="ti ti-report-off text-4xl text-gray-300"></i>
                                <span class="text-sm">No automated reports compiled yet.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE RESPONSIVE VIEW --}}
    <div class="block md:hidden divide-y divide-slate-100">
        @forelse($reports as $report)
            <div class="p-4 space-y-3.5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-slate-100 border border-slate-200 text-slate-700">
                            {{ ucfirst($report->type) }}
                        </span>
                        <div class="font-medium text-slate-800 text-sm mt-2">
                            <span class="text-xs text-slate-400 block font-normal uppercase tracking-wider mb-0.5">Source Engine</span>
                            <span class="text-xs font-semibold text-slate-500">🤖 System Auto</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Date</span>
                        <span class="text-xs text-slate-600 font-medium block mt-1">
                            {{ optional($report->generated_at)->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 bg-slate-50/60 rounded-xl p-3 border border-slate-100/80 text-xs">
                    <div>
                        <span class="text-slate-400">Data Volume:</span>
                        <span class="font-mono font-bold text-slate-700 ml-1">{{ number_format($report->total_records) }} rows</span>
                    </div>
                </div>

                <div class="pt-1">
                    <a href="{{ route('reports.show', $report) }}" class="w-full inline-flex items-center justify-center gap-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                        View Full Data <i class="ti ti-chevron-right text-xs text-slate-400"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center text-gray-400">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <i class="ti ti-report-off text-4xl text-gray-300"></i>
                    <span class="text-sm">No automated reports compiled yet.</span>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($reports, 'hasPages') && $reports->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $reports->links() }}
        </div>
    @endif
</div>

@endsection
