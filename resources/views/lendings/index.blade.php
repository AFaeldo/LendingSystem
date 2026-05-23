@extends('layouts.app')

@section('title', 'Lendings')
@section('page-title', 'Lendings')

@section('content')

<div class="flex items-center justify-end mb-6">
    <a href="{{ route('lendings.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold text-sm transition shadow-sm">
        <i class="ti ti-plus text-lg"></i>
        New Lending
    </a>
</div>

{{-- FILTERS --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
            <input type="text" placeholder="Search borrower or item..." class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 placeholder:text-gray-400" />
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
            <select class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-700">
                <option>All Status</option>
                <option>Active</option>
                <option>Returned</option>
                <option>Overdue</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Due Date</label>
            <input type="date" class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-400 focus:ring focus:ring-emerald-100 text-slate-600" />
        </div>

        <div class="flex items-end">
            <button class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                <i class="ti ti-filter text-lg mr-2"></i>
                Filter
            </button>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="text-sm font-medium text-slate-500 mb-2">Active Lending</p>
        <h2 class="text-4xl font-bold text-slate-900 tracking-tight">{{ $activeLendings ?? 0 }}</h2>
        <p class="text-xs text-slate-400 mt-2">Currently borrowed</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 border-red-500">
        <p class="text-sm font-medium text-slate-500 mb-2">Overdue Items</p>
        <h2 class="text-4xl font-bold text-slate-900 tracking-tight">6</h2>
        <p class="text-xs text-slate-400 mt-2">Needs immediate follow-up</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 border-blue-500">
        <p class="text-sm font-medium text-slate-500 mb-2">Returned Today</p>
        <h2 class="text-4xl font-bold text-slate-900 tracking-tight">12</h2>
        <p class="text-xs text-slate-400 mt-2">Successfully returned</p>
    </div>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800">Lending Records</h2>
            <p class="text-xs text-slate-500 mt-0.5">Complete transaction history</p>
        </div>

        <button class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition px-3 py-1.5 rounded-lg hover:bg-slate-50">
            <i class="ti ti-download text-base"></i>
            Export
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[860px] text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4">Borrower</th>
                    <th class="px-6 py-4">Item</th>
                    <th class="px-6 py-4 text-center">Quantity</th>
                    <th class="px-6 py-4">Date Borrowed</th>
                    <th class="px-6 py-4">Due Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($lendings as $lending)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $lending->borrower->firstname }} {{ $lending->borrower->lastname }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $lending->item->name }}</td>
                        <td class="px-6 py-4 text-center text-slate-600 font-medium">{{ $lending->quantity }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($lending->borrowed_at)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($lending->due_at)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                                {{ $lending->status === 'overdue' ? 'bg-red-50 border-red-100 text-red-700' : '' }}
                                {{ $lending->status === 'returned' ? 'bg-green-50 border-green-100 text-green-700' : '' }}
                                {{ $lending->status === 'active' ? 'bg-brand-50 border-brand-100 text-brand-700' : '' }}">
                                {{ ucfirst($lending->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('lendings.show', $lending) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                View <i class="ti ti-chevron-right text-xs"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
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
</div>

@endsection
