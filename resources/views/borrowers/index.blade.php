@extends('layouts.app')

@section('title', 'Borrowers')
@section('page-title', 'Borrowers')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 mb-6">
    <a href="{{ route('borrowers.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm shrink-0">
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

    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500 text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4 w-16">ID</th>
                    <th class="px-6 py-4">Last Name</th>
                    <th class="px-6 py-4">First Name</th>
                    <th class="px-6 py-4 w-24">Sex</th>
                    <th class="px-6 py-4 w-16">Age</th>
                    <th class="px-6 py-4">Purok/Sitio</th>
                    <th class="px-6 py-4">Contact</th>
                    <th class="px-6 py-4 w-28">Status</th>
                    <th class="px-6 py-4 text-right w-36">Actions</th>
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

    <div class="block md:hidden divide-y divide-slate-100">
        @forelse($borrowers as $b)
            <div class="p-4 space-y-3.5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="font-mono text-[10px] font-bold text-slate-400 block mb-0.5">#{{ $b->id }}</span>
                        <h3 class="font-bold text-slate-800 text-base leading-tight">
                            {{ $b->lastname }}, {{ $b->firstname }}
                        </h3>
                    </div>

                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border shrink-0
                        {{ ($b->status ?? 'active') === 'active' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-600' }}">
                        {{ ucfirst($b->status ?? 'Active') }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-2 bg-slate-50/60 rounded-xl p-3 border border-slate-100/80 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Sex / Age</span>
                        <div class="flex items-center gap-1.5 font-medium text-slate-700">
                            @if(strtolower($b->gender) === 'male' || strtolower($b->gender) === 'm')
                                <span class="text-blue-600">Male</span>
                            @else
                                <span class="text-pink-600">Female</span>
                            @endif
                            <span class="text-slate-300">•</span>
                            <span>{{ $b->age ?? '-' }} yrs old</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Purok/Sitio</span>
                        <span class="font-medium text-slate-700">{{ $b->purok }}</span>
                    </div>
                    <div class="col-span-2 border-t border-slate-200/60 pt-2 mt-1">
                        <span class="text-slate-400 block mb-0.5">Contact Number</span>
                        <span class="font-mono font-medium text-slate-700">{{ $b->contact ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('borrowers.edit', $b) }}" class="inline-flex items-center justify-center bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 py-2 rounded-xl text-xs font-semibold transition">
                        Edit Profile
                    </a>

                    <form action="{{ route('borrowers.destroy', $b) }}" method="POST" class="w-full" onsubmit="return confirm('Delete borrower profile?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 py-2 rounded-xl text-xs font-semibold transition cursor-pointer">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center text-gray-400">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <i class="ti ti-user-off text-4xl text-gray-300"></i>
                    <span class="text-sm">No borrower records available yet.</span>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($borrowers, 'hasPages') && $borrowers->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $borrowers->links() }}
        </div>
    @endif
</div>

@endsection
