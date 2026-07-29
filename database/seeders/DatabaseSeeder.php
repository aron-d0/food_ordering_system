<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => null,
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ],
        );

        User::updateOrCreate(
            ['username' => 'customer'],
            [
                'name' => 'Sample Customer',
                'email' => null,
                'password' => Hash::make('customer'),
                'role' => 'customer',
            ],
        );

        $foods = [
            ['Chicken Rice Meal', 'Rice Meals', 129, 'Crispy chicken served with steamed rice and savory sauce.', 30, 'images/gravy-chicken-chop-with-plain-rice.png'],
            ['Braised Beef Rice Bowl', 'Rice Meals', 159, 'Tender braised beef on warm rice with rich sauce.', 25, 'images/braised-beef-with-rice.png'],
            ['Pork Fried Rice', 'Fried Rice', 99, 'Classic fried rice with pork, egg, and vegetables.', 40, 'images/pork-chao-fan.png'],
            ['Beef Noodle Soup', 'Noodles', 145, 'Warm noodle soup with beef slices and flavorful broth.', 20, 'images/beef-mami.png'],
            ['Pancit Canton', 'Noodles', 119, 'Stir-fried noodles with vegetables and savory seasoning.', 35, 'images/pancit-canton.png'],
            ['Lumpiang Shanghai', 'Sides', 75, 'Crispy rolls served with sweet chili sauce.', 50, 'images/4pc-lumpiang-shanghai.png'],
            ['Wonton Soup', 'Sides', 69, 'Light soup with wontons and green onions.', 24, 'images/wonton-soup.png'],
            ['Iced Tea', 'Drinks', 45, 'Refreshing house iced tea.', 60, 'images/iced-tea.png'],
            ['Bottled Water', 'Drinks', 25, 'Cold bottled water.', 80, 'images/bottled-water.png'],
            ['Halo-Halo Special', 'Desserts', 119, 'A sweet shaved ice dessert with mixed toppings.', 20, 'images/halo-halo-supreme.png'],
        ];

        foreach ($foods as [$name, $category, $price, $description, $stock, $image]) {
            Food::updateOrCreate(
                ['name' => $name],
                [
                    'category' => $category,
                    'price' => $price,
                    'description' => $description,
                    'stock_quantity' => $stock,
                    'is_available' => true,
                    'image_url' => $image,
                ],
            );
        }
    }
}
