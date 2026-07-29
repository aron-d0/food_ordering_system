@extends('layouts.app', ['title' => 'Edit Food'])

@section('content')
    <section class="mb-7">
        <a class="inline-flex rounded-full bg-white px-5 py-3 font-black text-stone-700 shadow" href="{{ route('admin.foods.index') }}">← Back to Inventory</a>
        <p class="mt-7 font-black uppercase tracking-[0.3em] text-red-700">Menu Setup</p>
        <h1 class="mt-2 text-4xl font-black">Edit Menu Item</h1>
        <p class="mt-2 text-stone-600">Update item details exactly as they appear on the kiosk.</p>
    </section>

    @include('admin.foods.form', ['food' => $food, 'action' => route('admin.foods.update', $food), 'method' => 'PUT', 'button' => 'Save Changes'])
@endsection
