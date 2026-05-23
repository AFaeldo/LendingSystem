@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')

@section('content')

<div class="flex items-center justify-end mb-6">
    <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold text-sm transition shadow-sm">
        <i class="ti ti-plus text-lg"></i>
        New Item
    </a>
</div>

{{-- MAIN INVENTORY TABLE CARD --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800">Items</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage and track inventory assets and availability</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[860px] text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-center">Available</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-600">
                            {{ $item->item_code }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="h-10 w-10 rounded-xl object-cover border border-slate-100 shadow-sm shrink-0" />
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                        <i class="ti ti-package text-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-slate-800">{{ $item->name }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-slate-400 max-w-xs truncate">{{ $item->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ optional($item->category)->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center text-slate-700 font-semibold">
                            {{ $item->available }}
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-4">
                                <a href="{{ route('items.edit', $item) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                    Edit
                                </a>

                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-600 transition cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="ti ti-package-off text-4xl text-gray-300"></i>
                                <span class="text-sm">No items available yet.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $items->links() }}
        </div>
    @endif
</div>

@endsection
