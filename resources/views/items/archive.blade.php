@extends('layouts.app')

@section('title', 'Archived Inventory')
@section('page-title', 'Archived Inventory')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <a href="{{ route('items.index') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
            <i class="ti ti-arrow-left text-lg"></i>
            Back to Inventory
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="ti ti-archive text-slate-400"></i> Archived Asset Vault
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 w-32">Item Code</th>
                        <th class="px-6 py-4">Item Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-center w-20">Total</th>
                        <th class="px-6 py-4 w-32">Condition</th>
                        <th class="px-6 py-4 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($archivedItems as $item)
                        <tr class="bg-slate-50/30 text-slate-600">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">{{ $item->item_code }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-xs">{{ optional($item->category)->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-mono text-xs">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-xs">{{ $item->condition ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('items.restore-item', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Restore this item back to active inventory records?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 cursor-pointer bg-transparent border-0 p-0">Restore Item</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-archive text-4xl text-gray-300"></i>
                                    <span class="text-sm">No items found inside the archive historical records.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
