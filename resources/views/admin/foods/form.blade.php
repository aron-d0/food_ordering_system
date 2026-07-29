<form class="grid gap-6 rounded-3xl bg-white p-6 shadow-lg xl:grid-cols-[1fr_340px]" method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="form-label" for="name">Food Name</label>
            <input class="form-input" id="name" name="name" value="{{ old('name', $food?->name) }}" placeholder="Ex. Chicken Rice Meal" required>
        </div>

        <div>
            <label class="form-label" for="category">Category</label>
            <input class="form-input" id="category" name="category" value="{{ old('category', $food?->category) }}" placeholder="Ex. Rice Meals" required>
        </div>

        <div>
            <label class="form-label" for="price">Price</label>
            <input class="form-input" id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $food?->price) }}" required>
        </div>

        <div>
            <label class="form-label" for="stock_quantity">Stock Quantity</label>
            <input class="form-input" id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $food?->stock_quantity ?? 0) }}" required>
        </div>

        <div>
            <label class="form-label" for="is_available">Kitchen Status</label>
            <select class="form-input" id="is_available" name="is_available" required>
                <option value="1" @selected((string) old('is_available', $food?->is_available ?? '1') === '1')>Serving / Available</option>
                <option value="0" @selected((string) old('is_available', $food?->is_available) === '0')>Stopped / Unavailable</option>
            </select>
        </div>

        <div>
            <label class="form-label" for="image_url">Kiosk Image Path</label>
            <input class="form-input" id="image_url" name="image_url" value="{{ old('image_url', $food?->image_url) }}" placeholder="images/sample.png">
        </div>

        <div class="md:col-span-2">
            <label class="form-label" for="description">Menu Description</label>
            <textarea class="form-input min-h-40" id="description" name="description" placeholder="Short customer-facing item description...">{{ old('description', $food?->description) }}</textarea>
        </div>

        <div class="flex flex-wrap gap-3 md:col-span-2">
            <button class="rounded-[1.5rem] bg-red-700 px-7 py-4 text-lg font-black text-white shadow-xl" type="submit">{{ $button }}</button>
            <a class="rounded-[1.5rem] bg-stone-100 px-7 py-4 text-lg font-black text-stone-700" href="{{ route('admin.foods.index') }}">Cancel</a>
        </div>
    </div>

    <aside class="rounded-[2rem] bg-[#201a17] p-6 text-white">
        <p class="font-black uppercase tracking-[0.25em] text-yellow-300">Kiosk Preview</p>
        <div class="mt-5 overflow-hidden rounded-[2rem] bg-white text-stone-950">
            <img class="h-56 w-full object-cover" src="{{ old('image_url', $food?->image_url) ? asset(old('image_url', $food?->image_url)) : asset('images/coke.png') }}" alt="Food preview">
            <div class="p-5">
                <p class="text-sm font-black uppercase tracking-wide text-red-700">{{ old('category', $food?->category) ?: 'Category' }}</p>
                <h3 class="mt-2 text-xl font-black">{{ old('name', $food?->name) ?: 'Food Name' }}</h3>
                <p class="mt-2 text-stone-600">{{ old('description', $food?->description) ?: 'This is how the item will appear on the kiosk.' }}</p>
                <p class="mt-5 text-2xl font-black text-red-700">₱{{ number_format((float) old('price', $food?->price ?? 0), 2) }}</p>
            </div>
        </div>
    </aside>
</form>
