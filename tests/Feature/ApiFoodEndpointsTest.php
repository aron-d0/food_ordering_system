<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiFoodEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_food_api_requires_token(): void
    {
        $this->getJson('/api/foods')->assertUnauthorized();
    }

    public function test_login_api_returns_bearer_token(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin_test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->postJson('/api/login', [
            'username' => 'admin_test',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure(['user', 'token', 'token_type'])
            ->assertJson(['token_type' => 'Bearer']);
    }

    public function test_admin_token_can_crud_foods(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin_test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $create = $this->withToken($token)->postJson('/api/foods', [
            'name' => 'API Meal',
            'category' => 'Rice Meals',
            'price' => 150,
            'description' => 'Created through API',
            'stock_quantity' => 8,
            'is_available' => 1,
            'image_url' => null,
        ])->assertCreated();

        $foodId = $create->json('id');

        $this->withToken($token)
            ->getJson('/api/foods')
            ->assertOk()
            ->assertJsonFragment(['name' => 'API Meal']);

        $this->withToken($token)->putJson("/api/foods/{$foodId}", [
            'name' => 'Updated API Meal',
            'category' => 'Rice Meals',
            'price' => 175,
            'description' => 'Updated through API',
            'stock_quantity' => 6,
            'is_available' => 1,
            'image_url' => null,
        ])->assertOk()->assertJsonFragment(['name' => 'Updated API Meal']);

        $this->withToken($token)->deleteJson("/api/foods/{$foodId}")
            ->assertOk()
            ->assertJson(['message' => 'Food deleted successfully.']);

        $this->assertDatabaseMissing('foods', ['id' => $foodId]);
    }

    public function test_customer_token_cannot_create_food(): void
    {
        $customer = User::create([
            'name' => 'Customer',
            'username' => 'customer_test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->withToken($customer->createToken('customer-token')->plainTextToken)
            ->postJson('/api/foods', [
                'name' => 'Blocked Meal',
                'category' => 'Rice Meals',
                'price' => 100,
                'description' => 'Should fail',
                'stock_quantity' => 1,
                'is_available' => 1,
            ])
            ->assertForbidden();
    }
}
