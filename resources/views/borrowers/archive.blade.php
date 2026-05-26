@extends('layouts.app')

@section('title', 'Archived Borrowers')
@section('page-title', 'Archived Borrowers')

@section('content')

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">

        <a href="{{ route('borrowers.index') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
            <i class="ti ti-arrow-left text-lg"></i>
            Back to Active Profiles
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="ti ti-archive text-slate-400"></i> Archived Profile Directory
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 w-16">ID</th>
                        <th class="px-6 py-4">Borrower Name</th>
                        <th class="px-6 py-4 w-24">Sex</th>
                        <th class="px-6 py-4">Purok/Sitio</th>
                        <th class="px-6 py-4 text-right w-44">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($archivedBorrowers as $b)
                        <tr class="bg-slate-50/40 text-slate-600 hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">#{{ $b->id }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $b->lastname }}, {{ $b->firstname }}</td>
                            <td class="px-6 py-4 text-xs">
                                {{ ucfirst($b->gender ?? '-') }}
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $b->purok ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                {{-- UNARCHIVE / RESTORE FORM CONTROL --}}
                                <form action="{{ route('borrowers.restore', $b) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Restore borrower account profile to active status entries?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 h-8 px-3 rounded-lg text-xs font-semibold transition cursor-pointer">
                                        <i class="ti ti-refresh text-sm"></i> Restore Profile
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="ti ti-archive text-4xl text-gray-300"></i>
                                    <span class="text-sm">No archived profiles stored in historical vault logs.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
