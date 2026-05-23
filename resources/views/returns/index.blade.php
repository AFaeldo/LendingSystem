@extends('layouts.app')

@section('title', 'Returns')
@section('page-title', 'Returns')

@section('content')

<div class="flex items-center justify-end mb-6">
    <a href="{{ route('returns.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold text-sm transition shadow-sm">
        <i class="ti ti-plus text-lg"></i>
        New Return
    </a>
</div>

{{-- MAIN TABLE CARD --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800">Return Records</h2>
            <p class="text-xs text-slate-500 mt-0.5">Logs of items turned back into inventory</p>
        </div>

        <button class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition px-3 py-1.5 rounded-lg hover:bg-slate-50">
            <i class="ti ti-download text-base"></i>
            Export
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4">Borrower</th>
                    <th class="px-6 py-4">Item</th>
                    <th class="px-6 py-4 text-center">Quantity</th>
                    <th class="px-6 py-4">Returned At</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
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
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border bg-green-50 border-green-100 text-green-700">
                                Returned
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('returns.show', $return) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-600 hover:text-emerald-700 transition">
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

    @if($returns->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $returns->links() }}
        </div>
    @endif
</div>

@endsection
