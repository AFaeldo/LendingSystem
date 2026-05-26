<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Brgy. San Antonio Lending Tracker</title>

    {{-- TABLER ICONS CORE WEBFONT CDN (FIXES THE UNRENDERED EYE ICON BUG) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gradient-to-b from-brand-200 via-brand-400 to-brand-700 font-sans text-slate-900">

    <main class="flex min-h-screen items-center justify-center px-4 py-6">

        <div class="w-full max-w-md">

            {{-- LOGO + TITLE (TOP CENTER) --}}
            <div class="text-center mb-6 sm:mb-8">

                <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Barangay Logo"
                    class="mx-auto h-20 w-20 sm:h-24 sm:w-24 object-contain drop-shadow-md">

                <h1 class="mt-4 text-2xl sm:text-3xl font-black uppercase tracking-wide text-white drop-shadow">
                    BRGY. SAN ANTONIO
                </h1>

                <p class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-[0.25em] sm:tracking-[0.3em] text-white/90">
                    Lending Tracker System
                </p>

            </div>

            {{-- LOGIN CARD --}}
            <div class="rounded-3xl bg-white/95 backdrop-blur border border-white/30 p-6 sm:p-8 shadow-card">

                {{-- SUCCESS SYSTEM NOTIFICATIONS --}}
                @if (session('success'))
                    <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERROR SYSTEM ALERTS --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM PROCESSING LAYER --}}
                <form action="/login" method="POST" class="space-y-5">

                    @csrf

                    <div>
                        <label class="form-label">Username / Email</label>
                        <input type="text" name="email" class="form-input" placeholder="Enter username or email"
                            required>
                    </div>

                    <div>
                        <label class="form-label">Password</label>
                        {{-- RELATIVE CONTAINER FOR PERFECT EYE-BUTTON POSITIONING --}}
                        <div class="relative w-full">
                            <input type="password" name="password" id="password" class="form-input pr-10 w-full"
                                placeholder="Enter password" required>

                            {{-- TOGGLE ACTION EYE BUTTON (EXPLICT type="button" PREVENTS ACCIDENTAL FORM SUBMIT) --}}
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                <i id="password-toggle-icon" class="ti ti-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <button class="w-full btn btn-primary">
                        Login
                    </button>

                </form>

                {{-- CARD FOOTER META LINKS --}}
                <div class="mt-6 text-center text-sm">
                    <a href="/forgot-password" class="hover:underline font-medium text-brand-600 hover:text-brand-700">
                        Forgot Password?
                    </a>
                </div>

            </div> {{-- COMPONENT STRUCTURE FIX: Explicitly closes the Login Card --}}

        </div>

    </main>

    {{-- INLINE DOM MANIPULATION INTERMEDIARY LOGIC --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Swap layout into the active visible visibility state
                toggleIcon.className = 'ti ti-eye-off text-lg';
            } else {
                passwordInput.type = 'password';
                // Revert component back to standard dynamic password obfuscation
                toggleIcon.className = 'ti ti-eye text-lg';
            }
        }
    </script>
</body>

</html>
