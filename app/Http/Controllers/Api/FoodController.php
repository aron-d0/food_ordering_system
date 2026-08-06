<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return Food::orderBy('category')->orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $food = Food::create($this->validatedFood($request));

        return response()->json($food, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        return $food;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        $food->update($this->validatedFood($request));

        return $food;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        if ($food->orders()->exists()) {
            return response()->json([
                'message' => 'This food has orders and cannot be deleted.',
            ], 422);
        }

        $food->delete();

        return response()->json([
            'message' => 'Food deleted successfully.',
        ]);
    }

    private function validatedFood(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_available' => ['required', Rule::in([0, 1, true, false])],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
