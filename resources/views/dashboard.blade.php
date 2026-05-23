@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="p-5 transition bg-white border border-slate-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Active Lendings</p>
                    <h1 class="mt-2 text-3xl font-bold text-brand-700">
                        {{ $activeLendings ?? 0 }}
                    </h1>
                </div>
                <div class="p-3 text-brand-700 bg-brand-50 rounded-xl">
                    <i class="text-2xl ti ti-clipboard-list"></i>
                </div>
            </div>
        </div>

        <div class="p-5 transition bg-white border border-slate-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Overdue Items</p>
                    <h1 class="mt-2 text-3xl font-bold text-red-600">
                        {{ $overdue ?? 0 }}
                    </h1>
                </div>
                <div class="p-3 text-red-600 bg-red-50 rounded-xl">
                    <i class="text-2xl ti ti-alert-triangle"></i>
                </div>
            </div>
        </div>

        <div class="p-5 transition bg-white border border-slate-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Inventory</p>
                    <h1 class="mt-2 text-3xl font-bold text-brand-600">
                        {{ $totalItems ?? 0 }}
                    </h1>
                </div>
                <div class="p-3 text-brand-600 bg-brand-50 rounded-xl">
                    <i class="text-2xl ti ti-box"></i>
                </div>
            </div>
        </div>

        <div class="p-5 transition bg-white border border-slate-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Borrowers</p>
                    <h1 class="mt-2 text-3xl font-bold text-yellow-600">
                        {{ $totalBorrowers ?? 0 }}
                    </h1>
                </div>
                <div class="p-3 text-yellow-700 bg-yellow-50 rounded-xl">
                    <i class="text-2xl ti ti-users"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">

        <div class="flex items-center justify-between p-4 border-b border-slate-100">
            <h2 class="font-semibold text-gray-800">Recent Transactions</h2>
            <button class="text-sm font-medium text-brand-600 hover:text-brand-700">
                View All
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-500">
                        <th class="p-4 whitespace-nowrap">Borrower</th>
                        <th class="p-4 whitespace-nowrap">Item</th>
                        <th class="p-4 whitespace-nowrap">Due Date</th>
                        <th class="p-4 whitespace-nowrap">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @if(isset($recentTransactions) && $recentTransactions->count())

                        @foreach($recentTransactions as $tx)
                            <tr class="hover:bg-gray-50/50">
                                <td class="p-4 whitespace-nowrap">
                                    {{ $tx->borrower->firstname }} {{ $tx->borrower->lastname }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    {{ $tx->item->name }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    {{ optional($tx->due_at)->format('M d, Y') }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $tx->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-brand-100 text-brand-700' }}">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                    @else
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-database-off text-3xl text-gray-300"></i>
                                    <span>No transaction records available</span>
                                </div>
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>

    <div class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">

        <div class="p-4 border-b border-slate-100">
            <h2 class="font-semibold text-gray-800">Inventory Overview</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-gray-500">
                        <th class="p-4 text-left">Item</th>
                        <th class="p-4 text-center">Category</th>
                        <th class="p-4 text-center">Total</th>
                        <th class="p-4 text-center">Available</th>
                        <th class="p-4 text-center">Lent Out</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($inventoryOverview as $item)
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-4 font-medium text-slate-700">{{ $item->name }}</td>
                            <td class="p-4 text-center text-slate-600">
                                {{ optional($item->category)->name ?? '-' }}
                            </td>
                            <td class="p-4 text-center text-slate-600">{{ $item->quantity }}</td>
                            <td class="p-4 text-center text-slate-600">{{ $item->available }}</td>
                            <td class="p-4 text-center text-slate-600">
                                {{ $item->quantity - $item->available }}
                            </td>
                            <td class="p-4 text-center">
                                @if($item->available > 5)
                                    <span class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full border border-green-100">
                                        Healthy
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-50 rounded-full border border-yellow-100">
                                        Low Stock
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-box-off text-3xl text-gray-300"></i>
                                    <span>No inventory records available</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
