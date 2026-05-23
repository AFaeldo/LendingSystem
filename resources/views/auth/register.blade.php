<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Brgy. San Antonio Lending Tracker</title>

    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-brand-50 text-slate-900">

    <main class="flex min-h-screen items-center justify-center px-4 py-10">

        <div class="w-full max-w-lg">

            {{-- REGISTER CARD --}}
            <div class="rounded-[32px] border border-brand-200 bg-white p-8 shadow-card">

                {{-- HEADER --}}
                <div class="mb-8 text-center">

                    <img
                        src="{{ asset('img/logo.png') }}"
                        alt="Barangay Logo"
                        class="mx-auto h-24 w-24 object-contain"
                    >

                    <h1 class="mt-5 text-3xl font-black text-brand-900">
                        Create Account
                    </h1>

                    <p class="mt-2 text-sm text-slate-600">
                        Register with your Barangay account
                    </p>

                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERROR MESSAGE --}}
                @if($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- REGISTER FORM --}}
                <form action="/register" method="POST" class="space-y-5">

                    @csrf

                    {{-- NAME --}}
                    <div class="grid gap-4 sm:grid-cols-2">

                        <div>
                            <label for="firstname" class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="firstname"
                                name="firstname"
                                required
                                placeholder="Enter first name"
                                class="form-input"
                            >
                        </div>

                        <div>
                            <label for="lastname" class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="lastname"
                                name="lastname"
                                required
                                placeholder="Enter last name"
                                class="form-input"
                            >
                        </div>

                    </div>

                    {{-- EMAIL --}}
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

                    {{-- PASSWORD --}}
                    <div>

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            class="form-input"
                        >

                    </div>

                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        class="btn btn-primary w-full"
                    >
                        Register
                    </button>

                </form>

            </div>

            {{-- FOOTER --}}
            <p class="mt-6 text-center text-sm text-slate-700">

                Already have an account?

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
