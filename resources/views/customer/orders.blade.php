@extends('layouts.app', ['title' => 'Order History'])

@section('content')
    <section class="rounded-3xl bg-white p-6 shadow-lg">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="font-black uppercase tracking-[0.3em] text-red-700">Receipts</p>
                <h1 class="mt-2 text-4xl font-black leading-none">Order History</h1>
                <p class="mt-3 text-stone-600">Review your previous kiosk order tickets.</p>
            </div>
            <a class="rounded-xl bg-red-700 px-5 py-3 font-black text-white shadow-lg" href="{{ route('customer.menu') }}">Start New Order</a>
        </div>

        <div class="mt-8 grid gap-4">
            @forelse($orders as $order)
                <article class="grid gap-4 rounded-2xl border-2 border-stone-100 p-4 lg:grid-cols-[80px_1fr_180px] lg:items-center">
                    <img class="h-20 w-20 rounded-xl object-cover" src="{{ $order->food?->image_url ? asset($order->food->image_url) : asset('images/coke.png') }}" alt="{{ $order->food?->name }}">
                    <div>
                        <p class="font-black uppercase tracking-wide text-red-700">Ticket #{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <h2 class="text-xl font-black">{{ $order->food?->name }}</h2>
                        <p class="text-stone-600">Qty {{ $order->quantity }} · {{ $order->order_date?->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="lg:text-right">
                        <p class="text-2xl font-black text-red-700">₱{{ number_format($order->total_price, 2) }}</p>
                        <span class="mt-2 inline-block rounded-full px-4 py-2 text-sm font-black {{ $order->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </article>
            @empty
                <div class="grid min-h-[360px] place-items-center rounded-[2rem] border-4 border-dashed border-stone-100 text-center">
                    <div>
                        <p class="text-5xl">🧾</p>
                        <h2 class="mt-4 text-2xl font-black">No tickets yet.</h2>
                        <a class="mt-5 inline-flex rounded-xl bg-red-700 px-6 py-3 font-black text-white" href="{{ route('customer.menu') }}">Start Ordering</a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection
