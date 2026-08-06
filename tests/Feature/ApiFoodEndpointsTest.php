<?php

namespace Tests\Feature;

use App\Models\Food;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFoodEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_food_api_is_publicly_readable(): void
    {
        Food::create([
            'name' => 'Public API Meal',
            'category' => 'Rice Meals',
            'price' => 150,
            'description' => 'Visible through the public API',
            'stock_quantity' => 8,
            'is_available' => 1,
            'image_url' => null,
        ]);

        $this->getJson('/api/foods')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Public API Meal']);
    }

    public function test_public_api_can_crud_foods_directly(): void
    {
        $create = $this->postJson('/api/foods', [
            'name' => 'API Meal',
            'category' => 'Rice Meals',
            'price' => 150,
            'description' => 'Created through public API',
            'stock_quantity' => 8,
            'is_available' => 1,
            'image_url' => null,
        ])->assertCreated();

        $foodId = $create->json('id');

        $this->getJson("/api/foods/{$foodId}")
            ->assertOk()
            ->assertJsonFragment(['name' => 'API Meal']);

        $this->putJson("/api/foods/{$foodId}", [
            'name' => 'Updated API Meal',
            'category' => 'Rice Meals',
            'price' => 175,
            'description' => 'Updated through public API',
            'stock_quantity' => 6,
            'is_available' => 1,
            'image_url' => null,
        ])->assertOk()->assertJsonFragment(['name' => 'Updated API Meal']);

        $this->deleteJson("/api/foods/{$foodId}")
            ->assertOk()
            ->assertJson(['message' => 'Food deleted successfully.']);

        $this->assertDatabaseMissing('foods', ['id' => $foodId]);
    }
}
