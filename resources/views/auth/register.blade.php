@extends('layouts.app', ['title' => 'Register'])

@section('content')
    <section class="grid min-h-screen place-items-center bg-gradient-to-br from-red-700 via-red-900 to-[#201a17] p-6">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-7 shadow-lg">
            <a class="inline-flex rounded-full bg-stone-100 px-5 py-3 font-black text-stone-700" href="{{ route('login') }}">← Back to Login</a>
            <p class="mt-7 font-black uppercase tracking-[0.25em] text-red-700">Join QuickBite</p>
            <h1 class="mt-3 text-3xl font-black">Create Account</h1>
            <p class="mt-2 text-stone-600">Save your details and start ordering faster.</p>

            <form class="mt-7 grid gap-5" method="POST" action="{{ route('register.store') }}">
                @csrf
                <div>
                    <label class="form-label" for="name">Full Name</label>
                    <input class="form-input" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="form-label" for="username">Username</label>
                    <input class="form-input" id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username">
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="form-label" for="password">Password</label>
                        <input class="form-input" id="password" name="password" type="password" placeholder="At least 8 characters" required>
                    </div>
                    <div>
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input class="form-input" id="password_confirmation" name="password_confirmation" type="password" required>
                    </div>
                </div>
                <button class="h-12 rounded-xl bg-red-700 font-black text-white shadow-lg" type="submit">Create Account</button>
            </form>
        </div>
    </section>
@endsection
