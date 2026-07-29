@extends('layouts.app', ['title' => 'Order Queue'])

@section('content')
    <section class="mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="font-black uppercase tracking-[0.3em] text-red-700">Kitchen Queue</p>
            <h1 class="mt-2 text-4xl font-black">Order Management</h1>
            <p class="mt-2 text-stone-600">Update order progress from pending to preparing, completed, or cancelled.</p>
        </div>
        <div class="rounded-2xl bg-[#201a17] px-6 py-4 text-white shadow-lg">
            <p class="text-sm font-black uppercase text-yellow-300">Total Tickets</p>
            <p class="text-3xl font-black">{{ $orders->count() }}</p>
        </div>
    </section>

    <section class="grid gap-5">
        @forelse($orders as $order)
            <article class="grid gap-5 rounded-2xl bg-white p-5 shadow-lg xl:grid-cols-[1fr_220px_240px] xl:items-center">
                <div class="flex gap-4">
                    <img class="h-20 w-20 rounded-xl object-cover" src="{{ $order->food?->image_url ? asset($order->food->image_url) : asset('images/coke.png') }}" alt="{{ $order->food?->name }}">
                    <div>
                        <p class="text-sm font-black uppercase tracking-wide text-red-700">Ticket #{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <h2 class="text-xl font-black">{{ $order->food?->name }} × {{ $order->quantity }}</h2>
                        <p class="mt-1 text-stone-600">{{ $order->user?->name }} · {{ $order->user?->username }}</p>
                        <p class="text-stone-500">{{ $order->order_date?->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-black uppercase text-stone-500">Amount</p>
                    <p class="text-2xl font-black text-red-700">₱{{ number_format($order->total_price, 2) }}</p>
                    <span class="mt-3 inline-block rounded-full px-4 py-2 text-sm font-black {{ $order->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800') }}">{{ $order->status }}</span>
                </div>

                <div class="flex flex-wrap gap-2 xl:justify-end">
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <select class="h-11 rounded-xl border-2 border-stone-100 px-3 font-black" name="status" onchange="this.form.submit()">
                            @foreach(\App\Models\Order::STATUSES as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </form>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" data-confirm-message="Delete ticket #{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}?" data-confirm-title="Delete Order Ticket" data-confirm-text="Delete">
                        @csrf
                        @method('DELETE')
                        <button class="h-11 rounded-xl bg-red-100 px-4 font-black text-red-700" type="submit">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white p-10 text-center shadow-lg">
                <p class="text-4xl">🧾</p>
                <h2 class="mt-4 text-2xl font-black">No order tickets yet.</h2>
            </div>
        @endforelse
    </section>
@endsection
