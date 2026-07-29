<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FoodOrderingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_food_page(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin_test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.foods.store'), [
                'name' => 'Test Meal',
                'category' => 'Rice Meals',
                'price' => 99,
                'description' => 'Demo food',
                'stock_quantity' => 10,
                'is_available' => '1',
                'image_url' => null,
            ])
            ->assertRedirect(route('admin.foods.index'));

        $this->assertDatabaseHas('foods', [
            'name' => 'Test Meal',
            'category' => 'Rice Meals',
        ]);
    }

    public function test_admin_food_index_renders(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin_test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Food::create([
            'name' => 'Render Meal',
            'category' => 'Rice Meals',
            'price' => 99,
            'description' => 'Demo food',
            'stock_quantity' => 10,
            'is_available' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.foods.index'))
            ->assertOk()
            ->assertSee('Food Inventory')
            ->assertSee('Render Meal');
    }

    public function test_customer_can_checkout_cart_and_stock_decreases(): void
    {
        $customer = User::create([
            'name' => 'Customer',
            'username' => 'customer_test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $food = Food::create([
            'name' => 'Chicken Meal',
            'category' => 'Rice Meals',
            'price' => 120,
            'description' => 'Chicken with rice',
            'stock_quantity' => 5,
            'is_available' => true,
        ]);

        $this->actingAs($customer)
            ->post(route('customer.cart.add'), [
                'food_id' => $food->id,
                'quantity' => 2,
            ])
            ->assertRedirect(route('customer.menu', ['category' => $food->category]));

        $this->actingAs($customer)
            ->get(route('customer.cart.index'))
            ->assertOk()
            ->assertSee('Review Items')
            ->assertSee('Chicken Meal');

        $this->actingAs($customer)
            ->get(route('customer.checkout'))
            ->assertOk()
            ->assertSee('Confirm Checkout');

        $this->actingAs($customer)
            ->post(route('customer.checkout.place'))
            ->assertRedirect(route('customer.order.success'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'food_id' => $food->id,
            'quantity' => 2,
            'total_price' => 240,
            'status' => 'Pending',
        ]);

        $this->assertSame(3, $food->fresh()->stock_quantity);
        $this->assertSame([], session('cart', []));
    }

    public function test_customer_menu_renders(): void
    {
        $customer = User::create([
            'name' => 'Customer',
            'username' => 'customer_test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        Food::create([
            'name' => 'Menu Meal',
            'category' => 'Rice Meals',
            'price' => 120,
            'description' => 'Chicken with rice',
            'stock_quantity' => 5,
            'is_available' => true,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.menu'))
            ->assertOk()
            ->assertSee('Order Menu')
            ->assertSee('Menu Meal');
    }

    public function test_customer_is_blocked_from_admin_pages(): void
    {
        $customer = User::create([
            'name' => 'Customer',
            'username' => 'customer_test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
