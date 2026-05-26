@extends('layouts.app')

@section('title', 'Lendings')
@section('page-title', 'Lendings')

@section('content')

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <p class="text-sm font-medium text-slate-500 mb-2">Active Lending</p>
            <h2 class="text-4xl font-bold text-slate-900 tracking-tight">{{ $activeLendings ?? 0 }}</h2>
            <p class="text-xs text-slate-400 mt-2">Currently borrowed</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-slate-500 mb-2">Overdue Items</p>
            <h2 class="text-4xl font-bold text-slate-900 tracking-tight">{{ $overdueLendings ?? 0 }}</h2>
            <p class="text-xs text-slate-400 mt-2">Needs immediate follow-up</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 border-blue-500 sm:col-span-2 md:col-span-1">
            <p class="text-sm font-medium text-slate-500 mb-2">Returned Today</p>
            <h2 class="text-4xl font-bold text-slate-900 tracking-tight">{{ $returnedToday ?? 0 }}</h2>
            <p class="text-xs text-slate-400 mt-2">Successfully returned</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 mb-6">
        <a href="{{ route('lendings.create') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
            <i class="ti ti-plus text-lg"></i>
            New Lending
        </a>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                <input type="text" placeholder="Search borrower or item..."
                    class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 placeholder:text-gray-400" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <select
                    class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Returned</option>
                    <option>Overdue</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Due Date</label>
                <input type="date"
                    class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-600" />
            </div>

            <div class="flex items-end">
                <button
                    class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl font-semibold text-sm transition h-[38px]">
                    <i class="ti ti-filter text-lg mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">Lending Records</h2>
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
                        <th class="px-6 py-4">Date Borrowed</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 w-28">Status</th>
                        <th class="px-6 py-4 text-right w-24">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($lendings as $lending)
                        <tr class="hover:bg-gray-50/50 transition">
                            {{-- INAYOS NA CELL 1: ID NUMBER CELL TO MATCH THE HEADER ALIGNMENT --}}
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">
                                #{{ $lending->id }}
                            </td>

                            {{-- CELL 2: BORROWER NAME --}}
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $lending->borrower->firstname }} {{ $lending->borrower->lastname }}
                            </td>

                            {{-- CELL 3: ITEM NAME --}}
                            <td class="px-6 py-4 text-slate-600">{{ $lending->item->name }}</td>

                            {{-- CELL 4: QUANTITY --}}
                            <td class="px-6 py-4 text-center text-slate-600 font-medium">{{ $lending->quantity }}</td>

                            {{-- CELL 5: DATE BORROWED --}}
                            <td class="px-6 py-4 text-slate-600">{{ optional($lending->borrowed_at)->format('M d, Y') }}</td>

                            {{-- CELL 6: DUE DATE --}}
                            <td class="px-6 py-4 text-slate-600">{{ optional($lending->due_at)->format('M d, Y') }}</td>

                            {{-- CELL 7: STATUS --}}
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                                {{ $lending->status === 'overdue' ? 'bg-red-50 border-red-100 text-red-700' : '' }}
                                {{ $lending->status === 'returned' ? 'bg-green-50 border-green-100 text-green-700' : '' }}
                                {{ $lending->status === 'active' ? 'bg-brand-50 border-brand-100 text-brand-700' : '' }}">
                                    {{ ucfirst($lending->status) }}
                                </span>
                            </td>

                            {{-- CELL 8: ACTIONS --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('lendings.show', $lending) }}"
                                    class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                    View <i class="ti ti-chevron-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- INAYOS DING COLSPAN UPANG MAG-FIT SA WALONG HALIGI NG SCREEN --}}
                            <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-folder-off text-4xl text-gray-300"></i>
                                    <span class="text-sm">No lending records available yet.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW LAYOUT BLOCK --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($lendings as $lending)
                <div class="p-4 space-y-3.5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                {{ $lending->borrower->firstname }} {{ $lending->borrower->lastname }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-1">
                                <span class="text-slate-400 font-medium">Item:</span> {{ $lending->item->name }}
                            </p>
                        </div>

                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border shrink-0
                        {{ $lending->status === 'overdue' ? 'bg-red-50 border-red-100 text-red-700' : '' }}
                        {{ $lending->status === 'returned' ? 'bg-green-50 border-green-100 text-green-700' : '' }}
                        {{ $lending->status === 'active' ? 'bg-brand-50 border-brand-100 text-brand-700' : '' }}">
                            {{ ucfirst($lending->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 bg-slate-50/60 rounded-xl p-3 border border-slate-100/80 text-xs">
                        <div>
                            <span class="text-slate-400 block">Quantity</span>
                            <span class="font-semibold text-slate-700">{{ $lending->quantity }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Borrowed</span>
                            <span class="font-medium text-slate-700">{{ optional($lending->borrowed_at)->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Due Date</span>
                            <span class="font-medium text-slate-700">{{ optional($lending->due_at)->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="{{ route('lendings.show', $lending) }}"
                            class="w-full inline-flex items-center justify-center gap-1 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                            View Details <i class="ti ti-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class="ti ti-folder-off text-4xl text-gray-300"></i>
                        <span class="text-sm">No lending records available yet.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection
