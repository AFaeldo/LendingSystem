@extends('layouts.app')

@section('title', 'Borrower Profile')
@section('page-title', 'Borrower Profile')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- BACK TO LIST BUTTON --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('borrowers.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
            <i class="ti ti-arrow-left text-base"></i> Back to Borrowers
        </a>
        <span class="px-3 py-1 text-xs font-semibold rounded-full border
            {{ $borrower->status === 'active' ? 'bg-green-50 border-green-100 text-green-700' : 'bg-red-50 border-red-100 text-red-700' }}">
            {{ ucfirst($borrower->status) }} Account
        </span>
    </div>

    {{-- PROFILE CARD --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono text-slate-400 block mb-1">Borrower ID: #{{ $borrower->id }}</span>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $borrower->lastname }}, {{ $borrower->firstname }} {{ $borrower->middlename }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    <i class="ti ti-building text-base mr-1"></i>{{ $borrower->organization ?? 'No Organization' }}
                </p>
            </div>

            <a href="{{ route('borrowers.edit', $borrower) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition px-4 py-2 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100">
                <i class="ti ti-edit text-base"></i> Edit Profile
            </a>
        </div>

        {{-- PERSONAL DETAILS GRID --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 text-sm">
            <div>
                <span class="text-slate-400 block mb-1 font-medium">Gender</span>
                <span class="font-semibold text-slate-800">{{ ucfirst($borrower->gender) }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1 font-medium">Age</span>
                <span class="font-semibold text-slate-800">{{ $borrower->age }} yrs old</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1 font-medium">Contact Number</span>
                <span class="font-semibold text-slate-800 font-mono">{{ $borrower->contact ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1 font-medium">Full Address</span>
                <span class="font-semibold text-slate-800">
                    {{ $borrower->purok ? 'Purok ' . $borrower->purok . ',' : '' }} {{ $borrower->address }}
                </span>
            </div>
        </div>
    </div>

    {{-- LENDING TRANSACTIONS HISTORY TABLES --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Borrowing History & Records</h2>
            <p class="text-xs text-slate-400 mt-1">List of all active, overdue, and completed item transactions under this profile.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4 w-24">Trans ID</th>
                        <th class="px-6 py-4">Item Borrowed</th>
                        <th class="px-6 py-4 text-center w-20">Qty</th>
                        <th class="px-6 py-4">Date Borrowed</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 w-32">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($borrower->lendings as $lending)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $lending->id }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $lending->item->name }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-600">{{ $lending->quantity }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ optional($lending->borrowed_at)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ optional($lending->due_at)->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full border
                                    {{ $lending->status === 'overdue' ? 'bg-red-50 border-red-100 text-red-700' : '' }}
                                    {{ $lending->status === 'returned' ? 'bg-green-50 border-green-100 text-green-700' : '' }}
                                    {{ $lending->status === 'active' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : '' }}">
                                    {{ ucfirst($lending->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <i class="ti ti-history text-3xl text-slate-300"></i>
                                    <span class="text-sm">This borrower has no transaction logs yet.</span>
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
