@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
    <section class="mb-6 overflow-hidden rounded-3xl bg-[#201a17] text-white shadow-lg">
        <div class="grid gap-6 p-6 lg:grid-cols-[1fr_280px] lg:items-center">
            <div>
                <p class="font-black uppercase tracking-[0.3em] text-yellow-300">Today at a Glance</p>
                <h1 class="mt-3 text-4xl font-black leading-tight">Kitchen Dashboard</h1>
                <p class="mt-4 max-w-3xl text-lg text-stone-300">Monitor food availability, order queue, customer activity, and inventory readiness from one back-office station.</p>
            </div>
            <div class="rounded-2xl bg-red-600 p-5 shadow-inner">
                <p class="text-sm font-black uppercase tracking-widest text-red-100">Pending Queue</p>
                <p class="mt-2 text-4xl font-black">{{ $pendingOrderCount }}</p>
                <a class="mt-5 inline-flex rounded-full bg-yellow-300 px-5 py-3 font-black text-red-950" href="{{ route('admin.orders.index') }}">Open Orders</a>
            </div>
        </div>
    </section>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['Menu Items', $foodCount, '🍔', 'Total records'],
            ['Available', $availableFoodCount, '✅', 'Ready to sell'],
            ['Orders', $orderCount, '🧾', 'All time'],
            ['Pending', $pendingOrderCount, '⏳', 'Needs action'],
            ['Customers', $customerCount, '👥', 'Registered users'],
        ] as [$label, $value, $icon, $caption])
            <div class="rounded-2xl bg-white p-5 shadow-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-black uppercase tracking-wide text-stone-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-black text-red-700">{{ $value }}</p>
                        <p class="mt-2 font-semibold text-stone-500">{{ $caption }}</p>
                    </div>
                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-stone-100 text-3xl">{{ $icon }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <section class="mt-8 grid gap-6 xl:grid-cols-[1fr_430px]">
        <div class="rounded-2xl bg-white p-5 shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="font-black uppercase tracking-[0.25em] text-red-700">Live Queue</p>
                    <h2 class="mt-2 text-2xl font-black">Recent Orders</h2>
                </div>
                <a class="rounded-full bg-red-700 px-5 py-3 font-black text-white" href="{{ route('admin.orders.index') }}">Manage Queue</a>
            </div>

            <div class="mt-5 divide-y divide-stone-100">
                @forelse($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-4 py-4">
                        <div>
                            <p class="text-xl font-black">{{ $order->food?->name }} × {{ $order->quantity }}</p>
                            <p class="text-stone-500">{{ $order->user?->name }} · {{ $order->order_date?->format('M d, h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-red-700">₱{{ number_format($order->total_price, 2) }}</p>
                            <span class="rounded-full bg-yellow-100 px-4 py-2 text-sm font-black text-yellow-800">{{ $order->status }}</span>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center font-semibold text-stone-500">No orders yet.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl bg-[#201a17] p-5 text-white shadow-lg">
            <p class="font-black uppercase tracking-[0.25em] text-yellow-300">Shortcuts</p>
            <h2 class="mt-2 text-2xl font-black">Quick Actions</h2>
            <div class="mt-6 grid gap-3">
                <a class="rounded-xl bg-red-600 px-5 py-3 font-black transition hover:bg-red-700" href="{{ route('admin.foods.create') }}">+ Add Menu Item</a>
                <a class="rounded-xl bg-white/10 px-5 py-3 font-black transition hover:bg-white/15" href="{{ route('admin.foods.index') }}">Inventory Control</a>
                <a class="rounded-xl bg-white/10 px-5 py-3 font-black transition hover:bg-white/15" href="{{ route('admin.orders.index') }}">Kitchen Order Queue</a>
            </div>
        </div>
    </section>
@endsection
