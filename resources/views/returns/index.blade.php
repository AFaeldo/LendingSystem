@extends('layouts.app')

@section('title', 'Returns')
@section('page-title', 'Returns')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 mb-6">
        <a href="{{ route('returns.create') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
            <i class="ti ti-plus text-lg"></i>
            New Return
        </a>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">Return Records</h2>
            </div>

            <button
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition px-3 py-2 sm:py-1.5 rounded-xl sm:rounded-lg bg-slate-50 sm:bg-transparent hover:bg-slate-100/70 cursor-pointer">
                <i class="ti ti-download text-base"></i>
                Export
            </button>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 w-16">ID #</th>
                        <th class="px-6 py-4">Borrower</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4 text-center w-20">Qty</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Return Date</th>
                        <th class="px-6 py-4 w-32">Condition</th>
                        <th class="px-6 py-4 text-right w-24">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $return->lending->borrower->firstname }} {{ $return->lending->borrower->lastname }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $return->lending->item->name }}
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 font-medium">
                                {{ $return->quantity }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ optional($return->returned_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full border bg-green-50 border-green-100 text-green-700">
                                    Returned
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('returns.show', $return) }}"
                                    class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                    View <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-folder-off text-4xl text-gray-300"></i>
                                    <span class="text-sm">No return transactions available yet.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($returns as $return)
                <div class="p-4 space-y-3.5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                {{ $return->lending->borrower->firstname }} {{ $return->lending->borrower->lastname }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-1">
                                <span class="text-slate-400 font-medium">Item:</span> {{ $return->lending->item->name }}
                            </p>
                        </div>

                        <span
                            class="px-2.5 py-0.5 text-xs font-medium rounded-full border shrink-0 bg-green-50 border-green-100 text-green-700">
                            Returned
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 bg-slate-50/60 rounded-xl p-3 border border-slate-100/80 text-xs">
                        <div>
                            <span class="text-slate-400 block">Quantity</span>
                            <span class="font-semibold text-slate-700">{{ $return->quantity }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Returned At</span>
                            <span
                                class="font-medium text-slate-700">{{ optional($return->returned_at)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="{{ route('returns.show', $return) }}"
                            class="w-full inline-flex items-center justify-center gap-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                            View Details <i class="ti ti-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class="ti ti-folder-off text-4xl text-gray-300"></i>
                        <span class="text-sm">No return transactions available yet.</span>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($returns->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $returns->links() }}
            </div>
        @endif
    </div>

@endsection
