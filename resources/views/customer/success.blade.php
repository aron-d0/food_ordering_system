@extends('layouts.app', ['title' => 'Order Placed'])

@section('content')
    <section class="mx-auto grid max-w-4xl place-items-center py-8">
        <div class="w-full overflow-hidden rounded-3xl bg-white text-center shadow-lg">
            <div class="bg-gradient-to-br from-red-700 to-red-950 p-8 text-white">
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-yellow-300 text-4xl text-red-950 shadow-inner">✓</div>
                <p class="mt-6 font-black uppercase tracking-[0.3em] text-yellow-300">Order Sent</p>
                <h1 class="mt-3 text-4xl font-black">Please wait for your number.</h1>
                @if($orderNumber)
                    <p class="mt-6 text-xl text-red-100">Order Number</p>
                    <p class="mt-2 text-6xl font-black text-yellow-300">#{{ $orderNumber }}</p>
                @endif
            </div>

            <div class="p-6">
                <div class="mx-auto max-w-2xl space-y-3 text-left">
                    @forelse($orders as $order)
                        <div class="flex justify-between rounded-2xl bg-stone-50 p-4">
                            <span class="font-black">{{ $order->food?->name }} × {{ $order->quantity }}</span>
                            <span class="font-black text-red-700">₱{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-stone-600">No recent order details found.</p>
                    @endforelse
                </div>

                <p class="mt-6 text-2xl font-black text-red-700">Total: ₱{{ number_format($orderTotal, 2) }}</p>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    <a class="rounded-xl bg-red-700 px-6 py-3 font-black text-white" href="{{ route('customer.menu') }}">Start New Order</a>
                    <a class="rounded-xl bg-stone-100 px-6 py-3 font-black text-stone-700" href="{{ route('customer.orders.index') }}">View Order History</a>
                </div>
            </div>
        </div>
    </section>
@endsection
