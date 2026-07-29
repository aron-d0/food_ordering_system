@extends('layouts.app', ['title' => 'Kiosk Menu'])

@section('content')
    <section class="grid gap-5 xl:grid-cols-[180px_1fr_340px]">
        <aside class="order-2 rounded-2xl bg-white p-4 shadow-lg xl:order-1 xl:sticky xl:top-24 xl:h-fit">
            <p class="px-3 text-xs font-black uppercase tracking-[0.25em] text-red-700">Categories</p>
            <div class="mt-4 flex gap-3 overflow-x-auto xl:flex-col xl:overflow-visible">
                <a class="kiosk-category {{ request('category') ? '' : 'is-active' }}" href="{{ route('customer.menu') }}">
                    <span class="text-2xl">🍱</span>
                    <span>All</span>
                </a>
                @foreach($categories as $category)
                    <a class="kiosk-category {{ request('category') === $category ? 'is-active' : '' }}"
                        href="{{ route('customer.menu', ['category' => $category]) }}">
                        <span class="text-2xl">
                            {{ str_contains(strtolower($category), 'drink') ? '🥤' : (str_contains(strtolower($category), 'dessert') ? '🍨' : '🍽️') }}
                        </span>
                        <span>{{ $category }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        <main class="order-1 xl:order-2">
            <div class="rounded-3xl bg-gradient-to-br from-red-700 via-red-800 to-red-950 p-6 text-white shadow-lg">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-black uppercase tracking-[0.25em] text-yellow-300">Self-service ordering</p>
                        <h1 class="mt-2 text-4xl font-black leading-none md:text-5xl">Order Menu</h1>
                        <p class="mt-3 max-w-2xl text-base font-semibold text-red-100">Choose a meal, add it to your tray, then checkout when ready.</p>
                    </div>
                    <div class="rounded-2xl bg-yellow-300 px-5 py-4 text-red-950 shadow-inner">
                        <p class="text-sm font-black uppercase tracking-widest">Tray Items</p>
                        <p class="text-4xl font-black">{{ $cartCount }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                @forelse($foods as $food)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-md transition hover:-translate-y-0.5 hover:shadow-xl">
                        <a href="{{ route('customer.foods.show', $food) }}" class="block">
                            <div class="relative h-40 overflow-hidden bg-stone-100">
                                <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    src="{{ $food->image_url ? asset($food->image_url) : asset('images/coke.png') }}"
                                    alt="{{ $food->name }}">
                                <span class="absolute left-4 top-4 rounded-full bg-white/95 px-4 py-2 text-sm font-black text-red-700 shadow">
                                    {{ $food->category }}
                                </span>
                            </div>
                        </a>
                        <div class="p-4">
                            <h2 class="min-h-12 text-xl font-black leading-tight">{{ $food->name }}</h2>
                            <p class="mt-2 line-clamp-2 min-h-10 text-stone-600">{{ $food->description }}</p>
                            <div class="mt-5 flex items-center justify-between">
                                <p class="text-2xl font-black text-red-700">₱{{ number_format($food->price, 0) }}</p>
                                <p class="rounded-full bg-stone-100 px-4 py-2 text-sm font-black text-stone-600">{{ $food->stock_quantity }} left</p>
                            </div>
                            <form class="mt-5 grid grid-cols-[1fr_auto] gap-3" method="POST" action="{{ route('customer.cart.add') }}">
                                @csrf
                                <input type="hidden" name="food_id" value="{{ $food->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="rounded-xl bg-red-700 px-4 py-3 font-black text-white shadow transition hover:bg-red-800" type="submit">
                                    Add
                                </button>
                                <a class="rounded-xl border-2 border-stone-100 px-4 py-3 font-black text-stone-700 transition hover:bg-stone-50"
                                    href="{{ route('customer.foods.show', $food) }}">
                                    Details
                                </a>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="card col-span-full text-center">
                        <p class="text-xl font-bold text-stone-600">No available food items yet.</p>
                    </div>
                @endforelse
            </div>
        </main>

        <aside class="order-3 rounded-2xl bg-[#201a17] p-5 text-white shadow-lg xl:sticky xl:top-24 xl:h-fit">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.25em] text-yellow-300">Current Tray</p>
                    <h2 class="mt-1 text-2xl font-black">Your Order</h2>
                </div>
                <div class="grid h-11 w-11 place-items-center rounded-xl bg-red-600 text-xl">🧺</div>
            </div>

            <div class="mt-4 max-h-[360px] space-y-3 overflow-y-auto pr-1">
                @forelse($cartRows as $row)
                    <div class="rounded-3xl bg-white/10 p-4">
                        <div class="flex gap-3">
                            <img class="h-16 w-16 rounded-2xl object-cover" src="{{ $row->food->image_url ? asset($row->food->image_url) : asset('images/coke.png') }}" alt="{{ $row->food->name }}">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-black">{{ $row->food->name }}</p>
                                <p class="text-sm text-stone-300">Qty {{ $row->quantity }}</p>
                                <p class="font-black text-yellow-300">₱{{ number_format($row->subtotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="grid h-36 place-items-center rounded-2xl border-2 border-dashed border-white/15 text-center">
                        <div>
                            <p class="text-3xl">🛒</p>
                            <p class="mt-3 font-bold text-stone-300">Your tray is empty.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 border-t border-white/10 pt-5">
                <div class="flex items-center justify-between">
                    <span class="text-stone-300">Total</span>
                    <span class="text-2xl font-black text-yellow-300">₱{{ number_format($cartTotal, 2) }}</span>
                </div>

                @if($cartRows->isEmpty())
                    <button class="mt-4 w-full rounded-xl bg-white/10 px-5 py-3 font-black text-white/40" disabled>
                        Add Items First
                    </button>
                @else
                    <a class="mt-4 flex w-full items-center justify-center rounded-xl bg-yellow-300 px-5 py-3 font-black text-red-950 shadow-lg transition hover:bg-yellow-200"
                        href="{{ route('customer.checkout') }}">
                        Checkout
                    </a>
                    <a class="mt-3 flex w-full items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-black text-white transition hover:bg-white/15"
                        href="{{ route('customer.cart.index') }}">
                        Edit Tray
                    </a>
                @endif
            </div>
        </aside>
    </section>
@endsection
