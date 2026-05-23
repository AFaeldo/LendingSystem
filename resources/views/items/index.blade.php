@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">

    <a href="{{ route('items.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
        <i class="ti ti-plus text-lg"></i>
        New Item
    </a>
</div>

{{-- MAIN CONTAINER INTERACTION HOOK --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4 w-32">Code</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-center w-32">Available</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">
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
                                <div class="max-w-xs">
                                    <div class="font-semibold text-slate-800 truncate">{{ $item->name }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-slate-400 truncate">{{ $item->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ optional($item->category)->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-block font-mono font-bold text-sm text-slate-800 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                {{ $item->available }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-4">
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

    <div class="block md:hidden divide-y divide-slate-100">
        @forelse($items as $item)
            <div class="p-4 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="h-12 w-12 rounded-xl object-cover border border-slate-100 shadow-sm shrink-0" />
                        @else
                            <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="ti ti-package text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">{{ $item->name }}</h3>
                            <span class="inline-block font-mono text-[10px] uppercase font-bold text-slate-400 mt-0.5 tracking-wider">
                                {{ $item->item_code }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Available</span>
                        <span class="text-sm font-mono font-bold text-slate-800 bg-slate-50 border border-slate-100 px-2 py-0.5 rounded-md mt-0.5 inline-block">
                            {{ $item->available }}
                        </span>
                    </div>
                </div>

                @if($item->description || $item->category)
                    <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100/60 text-xs space-y-1">
                        <div>
                            <span class="text-slate-400 font-medium">Category:</span>
                            <span class="text-slate-700 font-semibold ml-1">{{ optional($item->category)->name ?? '-' }}</span>
                        </div>
                        @if($item->description)
                            <p class="text-slate-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                        @endif
                    </div>
                @endif

                <div class="flex items-center gap-2 pt-1">
                    <a href="{{ route('items.edit', $item) }}" class="flex-1 inline-flex items-center justify-center bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                        <i class="ti ti-edit mr-1.5 text-sm text-slate-400"></i> Edit Profile
                    </a>

                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50/50 hover:bg-red-50 border border-red-100 text-red-600 py-2 rounded-xl text-xs font-semibold transition cursor-pointer">
                            <i class="ti ti-trash mr-1.5 text-sm opacity-80"></i> Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center text-gray-400">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <i class="ti ti-package-off text-4xl text-gray-300"></i>
                    <span class="text-sm">No items available yet.</span>
                </div>
            </div>
        @endforelse
    </div>

    @if($items->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $items->links() }}
        </div>
    @endif
</div>

@endsection
