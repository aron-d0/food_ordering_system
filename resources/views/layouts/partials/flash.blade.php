@if(session('success'))
    <div class="mb-5 rounded-[1.35rem] border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 rounded-[1.35rem] border border-red-200 bg-red-50 px-5 py-4 font-bold text-red-800 shadow-sm">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-[1.35rem] border border-amber-200 bg-amber-50 px-5 py-4 text-amber-900 shadow-sm">
        <p class="font-black">Please fix the following:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
