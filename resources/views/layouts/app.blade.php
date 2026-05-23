<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LendSysTracker')</title>

    @vite('resources/css/app.css')

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>

<body class="min-h-screen font-sans text-slate-900 bg-gradient-to-b from-brand-200 via-brand-400 to-brand-700">

<div class="flex min-h-screen">

    <div id="backdrop"
         class="fixed inset-0 bg-black/40 hidden z-40 md:hidden"
         onclick="toggleSidebar()"></div>

    <aside id="sidebar"
           class="fixed md:static inset-y-0 left-0 z-50 w-72 md:w-64
                  bg-white/80 backdrop-blur-md border-r border-white/30
                  transform -translate-x-full md:translate-x-0 transition-transform duration-300
                  flex flex-col shadow-xl">

        <div class="p-5 border-b border-white/30">
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white shadow">
                    <i class="ti ti-building-community text-xl"></i>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-brand-900 tracking-tight">LendSysTracker</h1>
                    <p class="text-xs text-slate-500 font-medium">Barangay San Antonio</p>
                </div>

            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">

            @php
                $active = 'bg-brand-100 text-brand-800 font-semibold shadow-sm';
            @endphp

            <a href="{{ url('/dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('dashboard') ? $active : '' }}">
                <i class="ti ti-layout-dashboard text-lg"></i> Dashboard
            </a>

            <a href="{{ url('/lendings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('lendings*') ? $active : '' }}">
                <i class="ti ti-clipboard-list text-lg"></i> Lending
            </a>

            <a href="{{ url('/returns') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('returns*') ? $active : '' }}">
                <i class="ti ti-refresh text-lg"></i> Returns
            </a>

            <a href="{{ route('items.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('items*') ? $active : '' }}">
                <i class="ti ti-box text-lg"></i> Inventory
            </a>

            <a href="{{ url('/borrowers') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('borrowers*') ? $active : '' }}">
                <i class="ti ti-users text-lg"></i> Borrowers
            </a>

            <a href="{{ url('/reports') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('reports*') ? $active : '' }}">
                <i class="ti ti-report text-lg"></i> Reports
            </a>

        </nav>

        <div class="p-4 border-t border-white/30 bg-white/40">
            <div class="flex items-center justify-between">

                <div>
                    <p class="font-semibold text-slate-800 text-sm">Admin</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>

                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-600 transition p-1.5 rounded-lg hover:bg-red-50">
                        <i class="ti ti-logout text-xl"></i>
                    </button>
                </form>

            </div>
        </div>

    </aside>

    <main class="flex-1 md:ml-0">

        <header class="sticky top-0 z-30 bg-white/70 backdrop-blur border-b border-white/30 px-6 py-4">

            <div class="flex items-center justify-between max-w-7xl mx-auto w-full">

                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()"
                            class="md:hidden text-brand-900 text-2xl p-1 hover:bg-brand-50 rounded-lg transition">
                        <i class="ti ti-menu-2"></i>
                    </button>

                    <div>
                        <h2 class="text-2xl font-bold text-brand-900 tracking-tight">
                            @yield('page-title')
                        </h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Welcome back, Admin</p>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-600 bg-white/50 border border-white/40 px-3 py-1.5 rounded-xl shadow-sm">
                    <i class="ti ti-calendar text-brand-600"></i>
                    {{ now()->format('F d, Y') }}
                </div>

            </div>

        </header>

        <section class="p-6 max-w-7xl mx-auto w-full space-y-6">

            @if(session('success'))
                <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-700 shadow-sm flex items-center gap-2">
                    <i class="ti ti-circle-check text-lg"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-700 shadow-sm">
                    <div class="flex items-center gap-2 mb-1.5">
                        <i class="ti ti-circle-x text-lg"></i>
                        <span class="text-sm font-semibold">Please correct the errors below:</span>
                    </div>
                    <ul class="space-y-1 text-sm pl-7 list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </section>

    </main>

</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('backdrop');

        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }
</script>

</body>
</html>
