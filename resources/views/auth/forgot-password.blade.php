<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | Brgy. San Antonio Lending Tracker</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-brand-50 text-slate-900">

    <main class="flex min-h-screen items-center justify-center px-4 py-10">

        <div class="w-full max-w-md">

            <div class="rounded-[32px] border border-brand-200 bg-white p-8 shadow-card">

                <div class="mb-8 text-center">

                   <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Barangay Logo"
                    class="mx-auto h-24 w-24 object-contain drop-shadow-md">

                    <h1 class="mt-5 text-3xl font-black text-brand-900">
                        Forgot Password
                    </h1>

                    <p class="mt-2 text-sm text-slate-600">
                        Enter your email to receive reset instructions.
                    </p>

                </div>

                @if(session('success'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/forgot-password" method="POST" class="space-y-5">

                    @csrf

                    <div>
                        <label for="email" class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="Enter your email"
                            class="form-input"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-full"
                    >
                        Send Reset Link
                    </button>

                </form>

            </div>

            <p class="mt-6 text-center text-sm text-slate-700">

                Remembered your password?

                <a
                    href="/login"
                    class="font-semibold text-brand-700 hover:text-brand-900"
                >
                    Login
                </a>

            </p>

        </div>

    </main>

</body>
</html>
