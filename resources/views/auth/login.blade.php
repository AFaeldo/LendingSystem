<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Brgy. San Antonio Lending Tracker</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gradient-to-b from-brand-200 via-brand-400 to-brand-700 font-sans text-slate-900">

<main class="flex min-h-screen items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- LOGO + TITLE (TOP CENTER) --}}
        <div class="text-center mb-8">

            <img
                src="{{ asset('img/logo.png') }}"
                alt="Barangay Logo"
                class="mx-auto h-24 w-24 object-contain drop-shadow-md"
            >

            <h1 class="mt-5 text-3xl font-black uppercase tracking-wide text-white drop-shadow">
                BRGY. SAN ANTONIO
            </h1>

            <p class="mt-1 text-sm font-semibold uppercase tracking-[0.3em] text-white/90">
                Lending Tracker System
            </p>

        </div>

        {{-- LOGIN CARD --}}
        <div class="rounded-3xl bg-white/95 backdrop-blur border border-white/30 p-8 shadow-card">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERRORS --}}
            @if($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form action="/login" method="POST" class="space-y-5">

                @csrf

                <div>
                    <label class="form-label">Username / Email</label>
                    <input type="text" name="email" class="form-input" placeholder="Enter username or email" required>
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter password" required>
                </div>

                <button class="w-full btn btn-primary">
                    Login
                </button>

            </form>

        </div>

        {{-- FOOTER --}}
        <div class="mt-6 text-center text-sm text-white/90">
            <a href="/forgot-password" class="hover:underline font-medium">
                Forgot Password?
            </a>
        </div>

    </div>

</main>

</body>
</html>
