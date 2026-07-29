@extends('layouts.app', ['title' => 'Checkout'])

@section('content')
    <section class="grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="rounded-3xl bg-white p-6 shadow-lg">
            <a class="inline-flex rounded-full bg-stone-100 px-5 py-3 font-black text-stone-700" href="{{ route('customer.cart.index') }}">← Back to Tray</a>
            <p class="mt-8 font-black uppercase tracking-[0.3em] text-red-700">Final Step</p>
            <h1 class="mt-3 text-4xl font-black leading-none">Confirm Checkout</h1>
            <p class="mt-3 max-w-3xl text-base text-stone-600">Review your tray one last time before sending it to the counter.</p>

            <div class="mt-8 grid gap-4">
                @foreach($cartRows as $row)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-stone-50 p-4">
                        <div class="flex items-center gap-4">
                            <img class="h-16 w-16 rounded-xl object-cover" src="{{ $row->food->image_url ? asset($row->food->image_url) : asset('images/coke.png') }}" alt="{{ $row->food->name }}">
                            <div>
                                <h3 class="text-xl font-black">{{ $row->food->name }}</h3>
                                <p class="text-stone-600">Qty {{ $row->quantity }} · ₱{{ number_format($row->food->price, 2) }} each</p>
                            </div>
                        </div>
                        <p class="text-xl font-black text-red-700">₱{{ number_format($row->subtotal, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <aside class="rounded-3xl bg-[#201a17] p-6 text-white shadow-lg">
            <p class="font-black uppercase tracking-[0.3em] text-yellow-300">Payment</p>
            <h2 class="mt-2 text-3xl font-black">Order Total</h2>
            <div class="mt-6 rounded-2xl bg-yellow-300 p-5 text-red-950">
                <p class="text-sm font-black uppercase tracking-widest">Amount Due</p>
                <p class="mt-2 text-4xl font-black">₱{{ number_format($cartTotal, 2) }}</p>
            </div>

            <div class="mt-5 grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-white/10 p-3 text-center font-black">Cash</div>
                <div class="rounded-xl bg-white/10 p-3 text-center font-black">Card</div>
                <div class="rounded-xl bg-white/10 p-3 text-center font-black">E-Wallet</div>
            </div>

            <form class="mt-7" method="POST" action="{{ route('customer.checkout.place') }}" data-confirm-title="Place Order" data-confirm-message="Send this order to the counter?" data-confirm-text="Place Order">
                @csrf
                <button class="h-14 w-full rounded-xl bg-red-600 text-lg font-black text-white shadow-lg transition hover:bg-red-700" type="submit">
                    Submit Order
                </button>
            </form>
        </aside>
    </section>
@endsection
