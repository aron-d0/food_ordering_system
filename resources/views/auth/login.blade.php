@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <section class="grid min-h-screen lg:grid-cols-[1fr_480px]">
        <div class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-800 to-red-950 p-8 text-white lg:p-10">
            <div class="absolute -right-28 -top-28 h-80 w-80 rounded-full bg-yellow-300/20 blur-3xl"></div>
            <div class="relative flex h-full flex-col justify-between">
                <div class="flex items-center gap-4">
                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-yellow-300 text-3xl text-red-950">🍽️</div>
                    <div>
                        <h1 class="text-3xl font-black">QuickBite</h1>
                        <p class="font-semibold text-red-100">Fresh meals, ready when you are.</p>
                    </div>
                </div>

                <div class="py-12">
                    <p class="font-black uppercase tracking-[0.35em] text-yellow-300">Tap. Order. Serve.</p>
                    <h2 class="mt-5 max-w-3xl text-5xl font-black leading-tight">Order your favorites in just a few taps.</h2>
                    <p class="mt-5 max-w-2xl text-lg text-red-100">Choose from the menu, review your tray, and send your order straight to the counter.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-white/10 p-4 font-black">Hot Meals</div>
                    <div class="rounded-2xl bg-white/10 p-4 font-black">Easy Checkout</div>
                    <div class="rounded-2xl bg-white/10 p-4 font-black">Fast Pickup</div>
                </div>
            </div>
        </div>

        <div class="grid place-items-center bg-[#f7efe3] p-6">
            <div class="w-full max-w-md rounded-3xl bg-white p-7 shadow-lg">
                <p class="font-black uppercase tracking-[0.25em] text-red-700">Welcome Back</p>
                <h2 class="mt-3 text-3xl font-black">Sign In</h2>
                <p class="mt-2 text-stone-600">Enter your account details to continue.</p>

                <form class="mt-7 space-y-5" method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div>
                        <label class="form-label" for="username">Username</label>
                        <input class="form-input" id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username">
                    </div>
                    <div>
                        <label class="form-label" for="password">Password</label>
                        <input class="form-input" id="password" name="password" type="password" required>
                    </div>
                    <button class="h-12 w-full rounded-xl bg-red-700 font-black text-white shadow-lg" type="submit">Continue</button>
                </form>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button type="button" class="rounded-xl bg-red-50 px-4 py-3 text-sm font-black text-red-700" data-demo-login="admin">
                        Admin Demo
                    </button>
                    <button type="button" class="rounded-xl bg-red-50 px-4 py-3 text-sm font-black text-red-700" data-demo-login="customer">
                        Customer Demo
                    </button>
                </div>

                <a class="mt-4 flex h-12 w-full items-center justify-center rounded-xl border-2 border-stone-100 font-black text-stone-800" href="{{ route('google.redirect') }}">Continue with Google</a>
                <a class="mt-4 flex h-12 w-full items-center justify-center rounded-xl bg-stone-100 font-black text-stone-700" href="{{ route('register') }}">Create Account</a>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-demo-login]').forEach((button) => {
            button.addEventListener('click', () => {
                const value = button.dataset.demoLogin;
                document.getElementById('username').value = value;
                document.getElementById('password').value = value;
            });
        });
    </script>
@endsection
