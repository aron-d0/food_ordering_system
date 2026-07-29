@extends('layouts.app', ['title' => $food->name])

@section('content')
    <section class="grid gap-6 lg:grid-cols-[1fr_460px]">
        <div class="overflow-hidden rounded-3xl bg-stone-100 shadow-lg">
            <img class="h-[460px] w-full object-cover" src="{{ $food->image_url ? asset($food->image_url) : asset('images/coke.png') }}" alt="{{ $food->name }}">
        </div>

        <div class="flex flex-col rounded-3xl bg-white p-6 shadow-lg">
            <a class="mb-6 inline-flex w-fit items-center rounded-full bg-stone-100 px-5 py-3 font-black text-stone-700" href="{{ route('customer.menu', ['category' => $food->category]) }}">
                ← Back to Menu
            </a>

            <p class="font-black uppercase tracking-[0.3em] text-red-700">{{ $food->category }}</p>
            <h1 class="mt-3 text-4xl font-black leading-tight">{{ $food->name }}</h1>
            <p class="mt-4 text-base leading-relaxed text-stone-600">{{ $food->description }}</p>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-red-700 p-5 text-white">
                    <p class="text-sm font-black uppercase tracking-widest text-red-100">Price</p>
                    <p class="mt-2 text-3xl font-black">₱{{ number_format($food->price, 0) }}</p>
                </div>
                <div class="rounded-2xl bg-yellow-300 p-5 text-red-950">
                    <p class="text-sm font-black uppercase tracking-widest">Available</p>
                    <p class="mt-2 text-3xl font-black">{{ $food->stock_quantity }}</p>
                </div>
            </div>

            <form class="mt-auto pt-8" method="POST" action="{{ route('customer.cart.add') }}">
                @csrf
                <input type="hidden" name="food_id" value="{{ $food->id }}">
                <label class="font-black text-stone-700" for="quantity">Choose Quantity</label>
                <div class="mt-3 grid grid-cols-[56px_1fr_56px] items-center gap-3">
                    <button class="h-14 rounded-xl bg-stone-100 text-2xl font-black text-red-700" type="button" data-qty-minus>-</button>
                    <input class="h-14 rounded-xl border-2 border-stone-100 text-center text-2xl font-black outline-none focus:border-red-500"
                        id="quantity" name="quantity" type="number" value="1" min="1" max="{{ $food->stock_quantity }}" required>
                    <button class="h-14 rounded-xl bg-stone-100 text-2xl font-black text-red-700" type="button" data-qty-plus>+</button>
                </div>

                <button class="mt-5 h-14 w-full rounded-xl bg-red-700 text-lg font-black text-white shadow-lg transition hover:bg-red-800" type="submit">
                    Add to Tray
                </button>
            </form>
        </div>
    </section>

    <script>
        const input = document.getElementById('quantity');
        document.querySelector('[data-qty-minus]').addEventListener('click', () => input.value = Math.max(1, Number(input.value) - 1));
        document.querySelector('[data-qty-plus]').addEventListener('click', () => input.value = Math.min(Number(input.max), Number(input.value) + 1));
    </script>
@endsection
