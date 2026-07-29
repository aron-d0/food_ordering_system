@extends('layouts.app', ['title' => 'Add Food'])

@section('content')
    <section class="mb-7">
        <a class="inline-flex rounded-full bg-white px-5 py-3 font-black text-stone-700 shadow" href="{{ route('admin.foods.index') }}">← Back to Inventory</a>
        <p class="mt-7 font-black uppercase tracking-[0.3em] text-red-700">Menu Setup</p>
        <h1 class="mt-2 text-4xl font-black">Add Menu Item</h1>
        <p class="mt-2 text-stone-600">Create a new customer-facing food item for the kiosk.</p>
    </section>

    @include('admin.foods.form', ['food' => null, 'action' => route('admin.foods.store'), 'method' => 'POST', 'button' => 'Add Food'])
@endsection
