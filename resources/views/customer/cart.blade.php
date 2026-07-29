@extends('layouts.app', ['title' => 'Tray'])

@section('content')
    <section class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <div class="rounded-3xl bg-white p-6 shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="font-black uppercase tracking-[0.3em] text-red-700">Order Tray</p>
                    <h1 class="mt-2 text-4xl font-black leading-none">Review Items</h1>
                </div>
                <a class="rounded-full bg-stone-100 px-6 py-4 text-lg font-black text-stone-700" href="{{ route('customer.menu') }}">+ More Food</a>
            </div>

            @if($cartRows->isEmpty())
                <div class="mt-8 grid min-h-[300px] place-items-center rounded-2xl border-4 border-dashed border-stone-100 text-center">
                    <div>
                        <p class="text-5xl">🛒</p>
                        <h2 class="mt-4 text-2xl font-black">Your tray is empty.</h2>
                        <a class="mt-5 inline-flex rounded-xl bg-red-700 px-6 py-3 font-black text-white" href="{{ route('customer.menu') }}">Start Ordering</a>
                    </div>
                </div>
            @else
                <div class="mt-8 space-y-4">
                    @foreach($cartRows as $row)
                        <article class="grid gap-4 rounded-2xl border-2 border-stone-100 p-4 md:grid-cols-[88px_1fr_auto] md:items-center">
                            <img class="h-20 w-20 rounded-xl object-cover" src="{{ $row->food->image_url ? asset($row->food->image_url) : asset('images/coke.png') }}" alt="{{ $row->food->name }}">
                            <div>
                                <p class="font-black uppercase tracking-wide text-red-700">{{ $row->food->category }}</p>
                                <h2 class="text-xl font-black">{{ $row->food->name }}</h2>
                                <p class="text-stone-600">₱{{ number_format($row->food->price, 2) }} each</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 md:justify-end">
                                <form class="flex items-center gap-2" method="POST" action="{{ route('customer.cart.update', $row->food) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input class="h-11 w-20 rounded-xl border-2 border-stone-100 text-center font-black" name="quantity" type="number" min="1" max="{{ $row->food->stock_quantity }}" value="{{ $row->quantity }}" required>
                                    <button class="h-11 rounded-xl bg-stone-100 px-4 font-black text-stone-700" type="submit">Set</button>
                                </form>
                                <p class="w-28 text-right text-xl font-black text-red-700">₱{{ number_format($row->subtotal, 2) }}</p>
                                <form method="POST" action="{{ route('customer.cart.remove', $row->food) }}" data-confirm-title="Remove Item" data-confirm-message="Remove {{ $row->food->name }} from the tray?" data-confirm-text="Remove">
                                    @csrf
                                    @method('DELETE')
                                    <button class="h-11 rounded-xl bg-red-100 px-4 font-black text-red-700" type="submit">Remove</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <aside class="rounded-3xl bg-[#201a17] p-6 text-white shadow-lg">
            <p class="font-black uppercase tracking-[0.3em] text-yellow-300">Summary</p>
            <h2 class="mt-2 text-2xl font-black">Checkout</h2>
            <div class="mt-7 space-y-4">
                @forelse($cartRows as $row)
                    <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                        <span class="text-stone-300">{{ $row->food->name }} × {{ $row->quantity }}</span>
                        <span class="font-black">₱{{ number_format($row->subtotal, 2) }}</span>
                    </div>
                @empty
                    <p class="text-stone-400">No items selected.</p>
                @endforelse
            </div>
            <div class="mt-6 rounded-2xl bg-yellow-300 p-5 text-red-950">
                <p class="font-black uppercase tracking-widest">Total</p>
                <p class="mt-2 text-3xl font-black">₱{{ number_format($cartTotal, 2) }}</p>
            </div>
            @if(!$cartRows->isEmpty())
                <a class="mt-5 flex h-14 items-center justify-center rounded-xl bg-red-600 text-lg font-black text-white shadow-lg" href="{{ route('customer.checkout') }}">Proceed to Pay</a>
                <form class="mt-3" method="POST" action="{{ route('customer.cart.clear') }}" data-confirm-title="Clear Tray" data-confirm-message="Remove all items from this order tray?" data-confirm-text="Clear Tray">
                    @csrf
                    @method('DELETE')
                    <button class="h-12 w-full rounded-xl bg-white/10 font-black text-white" type="submit">Clear Tray</button>
                </form>
            @endif
        </aside>
    </section>
@endsection
