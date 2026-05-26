@extends('layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 mb-6">

        <div class="flex items-center gap-2 w-full sm:w-auto">
            {{-- VIEW ARCHIVE LINK BUTTON --}}
            <a href="{{ route('items.archive') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
                <i class="ti ti-archive text-lg text-slate-500"></i>
                View Archives
            </a>

            {{-- NEW ITEM BUTTON --}}
            <a href="{{ route('items.create') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0 cursor-pointer">
                <i class="ti ti-plus text-lg"></i>
                New Item
            </a>
        </div>
    </div>

    {{-- MAIN CONTAINER INTERACTION HOOK --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">Item Records</h2>
            </div>
        </div>

        {{-- DESKTOP VIEW COMPONENT --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 w-32">Item Code</th>
                        <th class="px-6 py-4">Item Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-center w-20">Total</th>
                        <th class="px-6 py-4 text-center w-24">Available</th>
                        <th class="px-6 py-4 text-center w-20">Lent</th>
                        <th class="px-6 py-4 w-32">Condition</th>
                        <th class="px-6 py-4 w-28">Availability</th>
                        <th class="px-6 py-4 text-right w-36">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50/40 transition">
                            {{-- 1. ITEM CODE --}}
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500 whitespace-nowrap">
                                {{ $item->item_code }}
                            </td>

                            {{-- 2. NAME ONLY --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="max-w-xs">
                                        <div class="font-semibold text-slate-800 truncate">{{ $item->name }}</div>
                                        @if ($item->description)
                                            <div class="text-xs text-slate-400 truncate max-w-[200px]">
                                                {{ $item->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- 3. CATEGORY --}}
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ optional($item->category)->name ?? '-' }}
                            </td>

                            {{-- 4. TOTAL QUANTITY --}}
                            <td class="px-6 py-4 text-center font-mono font-medium text-slate-700 whitespace-nowrap">
                                {{ $item->quantity }}
                            </td>

                            {{-- 5. AVAILABLE QUANTITY --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span
                                    class="inline-block font-mono font-bold text-sm text-emerald-700 bg-emerald-50/60 px-2.5 py-1 rounded-lg border border-emerald-100/50">
                                    {{ $item->available }}
                                </span>
                            </td>

                            {{-- 6. LENT QUANTITY --}}
                            <td class="px-6 py-4 text-center font-mono text-slate-500 whitespace-nowrap">
                                {{ $item->quantity - $item->available }}
                            </td>

                            {{-- 7. CONDITION --}}
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                @if ($item->condition == 'Good')
                                    Good
                                @elseif($item->condition == 'Fair')
                                    Fair
                                @elseif($item->condition == 'Poor')
                                    Poor
                                @elseif($item->condition == 'Damaged')
                                    Damaged
                                @else
                                    -
                                @endif
                            </td>

                            {{-- 8. AVAILABILITY BADGE SYSTEM --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($item->available > 0)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        In Stock
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                        All Lent Out
                                    </span>
                                @endif
                            </td>

                            {{-- 9. ACTIONS ROW CONTROLS WITH ARCHIVE OPTION --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-3.5">
                                    {{-- EDIT --}}
                                    <a href="{{ route('items.edit', $item) }}"
                                        class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition"
                                        title="Edit Item">
                                        Edit
                                    </a>

                                    {{-- ARCHIVE BUTTON --}}
                                    <form action="{{ route('items.archive-item', $item) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Archive this item? It can be retrieved later from your Archive records.')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-sm font-semibold text-amber-600 hover:text-amber-700 transition cursor-pointer bg-transparent border-0 p-0 inline-flex items-center gap-1"
                                            title="Archive Item">
                                            Archive
                                        </button>
                                    </form>

                                    {{-- DELETE
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Delete item permanently? This process cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-600 transition cursor-pointer bg-transparent border-0 p-0">
                                            Delete
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center text-gray-400">
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

        {{-- MOBILE VIEW COMPONENT --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($items as $item)
                <div class="p-4 space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if ($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
                                    class="h-12 w-12 rounded-xl object-cover border border-slate-100 shadow-sm shrink-0" />
                            @else
                                <div
                                    class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                    <i class="ti ti-package text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm leading-tight">{{ $item->name }}</h3>
                                <span
                                    class="inline-block font-mono text-[10px] uppercase font-bold text-slate-400 mt-0.5 tracking-wider">
                                    {{ $item->item_code }}
                                </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span
                                class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Available</span>
                            <span
                                class="text-sm font-mono font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md mt-0.5 inline-block">
                                {{ $item->available }} / {{ $item->quantity }}
                            </span>
                        </div>
                    </div>

                    @if ($item->description || $item->category || $item->condition)
                        <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100/60 text-xs space-y-1.5">
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-medium">Category:</span>
                                <span
                                    class="text-slate-700 font-semibold ml-1">{{ optional($item->category)->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-medium">Condition:</span>
                                <span class="text-slate-700 font-semibold ml-1">
                                    @if ($item->condition == 'Good')
                                        ✨ Good
                                    @elseif($item->condition == 'Fair')
                                        👍 Fair
                                    @elseif($item->condition == 'Poor')
                                        ⚠️ Poor
                                    @elseif($item->condition == 'Damaged')
                                        ❌ Damaged
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-medium">Lent Out:</span>
                                <span
                                    class="text-slate-600 font-mono font-medium ml-1">{{ $item->quantity - $item->available }}
                                    items</span>
                            </div>
                            @if ($item->description)
                                <p class="text-slate-500 border-t border-slate-100 pt-1.5 mt-1.5 line-clamp-2">
                                    {{ $item->description }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- MOBILE INTERACTION ACTIONS --}}
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <a href="{{ route('items.edit', $item) }}"
                            class="flex-1 inline-flex items-center justify-center bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                            <i class="ti ti-edit mr-1.5 text-sm text-slate-400"></i> Edit
                        </a>

                        <form action="{{ route('items.archive-item', $item) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center bg-amber-50/40 hover:bg-amber-50 border border-amber-100 text-amber-700 py-2 rounded-xl text-xs font-semibold transition cursor-pointer">
                                <i class="ti ti-archive mr-1.5 text-sm text-amber-500/80"></i> Archive
                            </button>
                        </form>

                        <form action="{{ route('items.destroy', $item) }}" method="POST"
                            class="w-full sm:flex-1 mt-1 sm:mt-0" onsubmit="return confirm('Delete item permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center bg-red-50/50 hover:bg-red-50 border border-red-100 text-red-600 py-2 rounded-xl text-xs font-semibold transition cursor-pointer">
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

        @if ($items->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $items->links() }}
            </div>
        @endif
    </div>

@endsection
