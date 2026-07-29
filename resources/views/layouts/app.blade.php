<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Food Ordering System') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $isCustomer = auth()->check() && auth()->user()->isCustomer();
    $cartCount = collect(session('cart', []))->sum();
@endphp

<body class="{{ $isAdmin ? 'bg-[#171412]' : 'bg-[#f7efe3]' }} min-h-screen font-sans text-stone-950">
    @if($isAdmin)
        <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
            <aside class="sticky top-0 z-40 flex min-h-screen flex-col bg-[#201a17] text-white shadow-xl max-lg:min-h-0">
                <div class="border-b border-white/10 p-5">
                    <div class="flex items-center gap-4">
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-red-600 text-xl shadow-lg">🔥</div>
                        <div>
                            <h1 class="text-xl font-black leading-tight">Back Office</h1>
                            <p class="text-sm text-stone-400">Store operations</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 space-y-1 p-4">
                    <a class="admin-nav" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
                    <a class="admin-nav" href="{{ route('admin.foods.index') }}">🍔 Food Inventory</a>
                    <a class="admin-nav" href="{{ route('admin.orders.index') }}">🧾 Order Queue</a>
                </nav>

                <div class="border-t border-white/10 p-4">
                    <div class="mb-3 rounded-2xl bg-white/5 p-4">
                        <p class="text-sm text-stone-400">Signed in as</p>
                        <p class="font-black">{{ auth()->user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full rounded-2xl bg-red-600 px-5 py-3 font-black text-white transition hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="min-h-screen bg-[#f6f0e8] p-5 lg:p-6">
                @include('layouts.partials.flash')
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    @else
        <div class="min-h-screen">
            @auth
                <header class="sticky top-0 z-40 border-b border-red-950/10 bg-[#b70f14] text-white shadow-xl">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-3">
                        <a href="{{ route('customer.menu') }}" class="flex items-center gap-4">
                            <div class="grid h-11 w-11 place-items-center rounded-xl bg-yellow-300 text-2xl shadow-inner">🍽️</div>
                            <div>
                                <h1 class="text-xl font-black leading-tight tracking-tight">QuickBite</h1>
                                <p class="text-sm font-semibold text-red-100">Tap. Review. Enjoy.</p>
                            </div>
                        </a>

                        <nav class="flex items-center gap-3">
                            <a class="kiosk-top-action" href="{{ route('customer.menu') }}">Menu</a>
                            <a class="kiosk-top-action" href="{{ route('customer.cart.index') }}">
                                Cart
                                @if($cartCount > 0)
                                    <span class="ml-2 rounded-full bg-yellow-300 px-3 py-1 text-sm text-red-950">{{ $cartCount }}</span>
                                @endif
                            </a>
                            <a class="kiosk-top-action max-md:hidden" href="{{ route('customer.orders.index') }}">Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-full bg-white px-5 py-3 font-black text-red-700 shadow transition hover:bg-yellow-100">
                                    Exit
                                </button>
                            </form>
                        </nav>
                    </div>
                </header>
            @endauth

            <main class="{{ auth()->check() ? 'mx-auto max-w-7xl px-4 py-5 lg:px-5' : 'min-h-screen' }}">
                @include('layouts.partials.flash')
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    @endif

    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-5 backdrop-blur-sm">
        <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-[#14100e] text-white shadow-[0_28px_80px_rgba(0,0,0,.4)]">
            <div class="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-yellow-300 via-red-500 to-orange-400"></div>
            <div class="grid gap-0 sm:grid-cols-[150px_1fr]">
                <div class="grid place-items-center bg-red-700 p-6">
                    <div class="grid h-16 w-16 place-items-center rounded-full bg-yellow-300 text-3xl shadow-inner">?</div>
                </div>
                <div class="p-7">
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-yellow-300">Please Confirm</p>
                    <h2 class="mt-3 text-2xl font-black" data-confirm-title>Confirm Action</h2>
                    <p class="mt-4 text-lg leading-relaxed text-stone-300" data-confirm-message>Are you sure?</p>
                    <div class="mt-7 grid grid-cols-2 gap-3">
                        <button type="button" class="rounded-2xl bg-white/10 px-5 py-4 font-black text-white transition hover:bg-white/15" data-confirm-cancel>Cancel</button>
                        <button type="button" class="rounded-2xl bg-yellow-300 px-5 py-4 font-black text-red-950 transition hover:bg-yellow-200" data-confirm-ok>Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmModal');
            if (!modal) return;

            const title = modal.querySelector('[data-confirm-title]');
            const message = modal.querySelector('[data-confirm-message]');
            const ok = modal.querySelector('[data-confirm-ok]');
            const cancel = modal.querySelector('[data-confirm-cancel]');
            let resolver = null;

            window.confirmAction = ({ confirmTitle = 'Confirm Action', confirmMessage = 'Are you sure?', confirmText = 'Confirm' } = {}) => {
                title.textContent = confirmTitle;
                message.textContent = confirmMessage;
                ok.textContent = confirmText;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                return new Promise(resolve => resolver = resolve);
            };

            const close = result => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (resolver) resolver(result);
                resolver = null;
            };

            ok.addEventListener('click', () => close(true));
            cancel.addEventListener('click', () => close(false));

            document.querySelectorAll('form[data-confirm-message]').forEach(form => {
                form.addEventListener('submit', async event => {
                    if (form.dataset.confirmed === 'true') {
                        form.dataset.confirmed = 'false';
                        return;
                    }

                    event.preventDefault();
                    const confirmed = await window.confirmAction(form.dataset);

                    if (confirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
