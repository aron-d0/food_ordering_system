@extends('layouts.app', ['title' => 'Food Inventory'])

@section('content')
    <section class="mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="font-black uppercase tracking-[0.3em] text-red-700">Menu Setup</p>
            <h1 class="mt-2 text-4xl font-black">Food Inventory</h1>
            <p class="mt-2 text-stone-600">Manage item pricing, stock quantity, category, and availability.</p>
        </div>
        <a class="rounded-xl bg-red-700 px-5 py-3 font-black text-white shadow-lg" href="{{ route('admin.foods.create') }}">+ Add Food</a>
    </section>

    <section class="grid gap-5 md:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-lg">
            <p class="text-sm font-black uppercase text-stone-500">Total Items</p>
            <p class="mt-2 text-3xl font-black text-red-700">{{ $foods->count() }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-lg">
            <p class="text-sm font-black uppercase text-stone-500">Categories</p>
            <p class="mt-2 text-3xl font-black text-red-700">{{ $categories->count() }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-lg">
            <p class="text-sm font-black uppercase text-stone-500">Available</p>
            <p class="mt-2 text-3xl font-black text-red-700">{{ $foods->where('is_available', true)->where('stock_quantity', '>', 0)->count() }}</p>
        </div>
    </section>

    <section class="mt-6 overflow-hidden rounded-2xl bg-white shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1060px]">
                <thead class="bg-[#201a17] text-left text-sm uppercase tracking-wide text-stone-200">
                    <tr>
                        <th class="px-5 py-4">Menu Item</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Stock</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($foods as $food)
                        <tr class="transition hover:bg-red-50/40">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <img class="h-16 w-16 rounded-xl object-cover" src="{{ $food->image_url ? asset($food->image_url) : asset('images/coke.png') }}" alt="{{ $food->name }}">
                                    <div>
                                        <p class="text-lg font-black">{{ $food->name }}</p>
                                        <p class="line-clamp-1 max-w-lg text-stone-500">{{ $food->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-bold">{{ $food->category }}</td>
                            <td class="px-5 py-4 text-lg font-black text-red-700">₱{{ number_format($food->price, 2) }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-stone-100 px-4 py-2 font-black">{{ $food->stock_quantity }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-4 py-2 text-sm font-black {{ $food->is_available && $food->stock_quantity > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $food->is_available && $food->stock_quantity > 0 ? 'Serving' : 'Stopped' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a class="rounded-xl bg-stone-100 px-4 py-2 font-black text-stone-700" href="{{ route('admin.foods.edit', $food) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.foods.destroy', $food) }}" data-confirm-message="Delete {{ $food->name }} from inventory?" data-confirm-title="Delete Menu Item" data-confirm-text="Delete">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-xl bg-red-100 px-4 py-2 font-black text-red-700" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-14 text-center font-semibold text-stone-500" colspan="6">No food items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
