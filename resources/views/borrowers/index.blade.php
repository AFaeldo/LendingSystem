@extends('layouts.app')

@section('title', 'Borrowers')
@section('page-title', 'Borrowers')

@section('content')

<div class="flex items-center justify-end mb-6">
    <a href="{{ route('borrowers.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl font-semibold text-sm transition shadow-sm">
        <i class="ti ti-plus text-lg"></i>
        Add Borrower
    </a>
</div>

{{-- MAIN BORROWERS TABLE CARD --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">Borrower Profiles</h2>
        <p class="text-xs text-slate-500 mt-0.5">Manage community borrower records, demographics, and active status</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px] text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4 w-16">ID</th>
                    <th class="px-6 py-4">Last Name</th>
                    <th class="px-6 py-4">First Name</th>
                    <th class="px-6 py-4">Sex</th>
                    <th class="px-6 py-4">Age</th>
                    <th class="px-6 py-4">Purok/Sitio</th>
                    <th class="px-6 py-4">Contact</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($borrowers as $b)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                            #{{ $b->id }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $b->lastname }}
                        </td>

                        <td class="px-6 py-4 text-slate-700">
                            {{ $b->firstname }}
                        </td>

                        <td class="px-6 py-4">
                            @if(strtolower($b->gender) === 'male' || strtolower($b->gender) === 'm')
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-50 text-blue-600 border border-blue-100">Male</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-pink-50 text-pink-600 border border-pink-100">Female</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $b->age ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $b->purok }}
                        </td>

                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                            {{ $b->contact ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                                {{ ($b->status ?? 'active') === 'active' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-600' }}">
                                {{ ucfirst($b->status ?? 'Active') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-4">
                                <a href="{{ route('borrowers.edit', $b) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                    Edit
                                </a>

                                <form action="{{ route('borrowers.destroy', $b) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete borrower profile?')">
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
                        <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="ti ti-user-off text-4xl text-gray-300"></i>
                                <span class="text-sm">No borrower records available yet.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($borrowers, 'hasPages') && $borrowers->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $borrowers->links() }}
        </div>
    @endif
</div>

@endsection
