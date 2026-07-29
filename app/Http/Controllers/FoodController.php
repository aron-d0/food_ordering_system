<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::orderBy('category')->orderBy('name')->get();

        return view('admin.foods.index', [
            'foods' => $foods,
            'categories' => $foods->pluck('category')->unique()->values(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.foods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Food::create($this->validatedFood($request));

        return redirect()->route('admin.foods.index')->with('success', 'Food item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        return redirect()->route('admin.foods.edit', $food);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        return view('admin.foods.edit', compact('food'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        $food->update($this->validatedFood($request));

        return redirect()->route('admin.foods.index')->with('success', 'Food item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        if ($food->orders()->exists()) {
            return back()->with('error', 'This food has orders, so it cannot be deleted. Mark it unavailable instead.');
        }

        $food->delete();

        return redirect()->route('admin.foods.index')->with('success', 'Food item deleted successfully.');
    }

    private function validatedFood(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_available' => ['required', Rule::in(['0', '1'])],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
