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

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white p-1 shadow-sm border border-slate-100">
                    <img src="{{ Vite::asset('resources/img/logo.png') }}"
                         alt="Barangay Logo"
                         class="h-full w-full object-contain drop-shadow-sm">
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

            <a href="{{ url('/borrowers') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('borrowers*') ? $active : '' }}">
                <i class="ti ti-users text-lg"></i> Borrowers
            </a>

            <a href="{{ url('/lendings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('lendings*') ? $active : '' }}">
                <i class="ti ti-clipboard-list text-lg"></i> Lending
            </a>

            <a href="{{ route('items.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('items*') ? $active : '' }}">
                <i class="ti ti-box text-lg"></i> Items
            </a>

            <a href="{{ url('/returns') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-white/60 hover:text-slate-900
               {{ request()->is('returns*') ? $active : '' }}">
                <i class="ti ti-refresh text-lg"></i> Returns
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
                    <button type="submit" class="text-slate-400 hover:text-red-600 transition p-1.5 rounded-lg hover:bg-red-50 cursor-pointer">
                        <i class="ti ti-logout text-xl"></i>
                    </button>
                </form>

            </div>
        </div>

    </aside>

    <main class="flex-1 md:ml-0 overflow-x-hidden">

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
                    </div>
                </div>

                {{-- RIGHT CONTENT AREA: NOTIFICATIONS & DATE --}}
                <div class="flex items-center gap-4">

                    {{-- 🔔 BELL NOTIFICATION DROP-DOWN MODULE --}}
                    <div class="relative">
                        <button onclick="toggleNotifications()" class="relative p-2 text-slate-500 hover:text-emerald-600 rounded-xl hover:bg-white/80 transition cursor-pointer shadow-sm border border-slate-100 bg-white/50">
                            <i class="ti ti-bell text-xl block"></i>

                            {{-- Red Alert Badge Counter --}}
                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 min-w-4 h-4 px-1 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Container Box --}}
                        <div id="notificationDropdown" class="absolute right-0 mt-3 w-80 bg-white border border-slate-200/80 rounded-2xl shadow-xl hidden z-50 overflow-hidden transform origin-top-right">
                            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Notifications</span>
                                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        {{ auth()->user()->unreadNotifications->count() }} New
                                    </span>
                                @endif
                            </div>

                            <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                                @if(auth()->check())
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <a href="{{ $notification->data['action_url'] ?? url('/reports') }}" class="p-3.5 hover:bg-slate-50/80 transition flex gap-3 items-start text-left block">
                                            <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></div>
                                            <div class="flex-1">
                                                <p class="text-xs text-slate-700 font-medium leading-relaxed">
                                                    {{ $notification->data['message'] ?? 'New automated record log generated.' }}
                                                </p>
                                                <span class="text-[10px] text-slate-400 font-mono mt-1.5 block">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-8 text-center text-slate-400 text-xs">
                                            <i class="ti ti-bell-off text-3xl text-slate-200 block mb-2"></i>
                                            All tasks balanced! No new alerts.
                                        </div>
                                    @endforelse
                                @else
                                    <div class="p-6 text-center text-slate-400 text-xs">Please log in to view notices.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- DATE BADGE --}}
                    <div class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-600 bg-white/50 border border-white/40 px-3 py-1.5 rounded-xl shadow-sm h-10">
                        <i class="ti ti-calendar text-brand-600"></i>
                        {{ now()->format('F d, Y') }}
                    </div>

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

    // JS Function para buksan/isarado ang Notification Box
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    }

    // Isarado ang dropdown kapag nag-click ang admin sa labas ng box
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notificationDropdown');
        const button = dropdown.previousElementSibling;
        if (!dropdown.contains(e.target) && !button.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>

</body>
</html>
