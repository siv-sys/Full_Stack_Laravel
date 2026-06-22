<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports', 'slug' => 'sports'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            ['category_id' => 1, 'name' => 'Wireless Headphones', 'slug' => 'wireless-headphones', 'description' => 'High-quality wireless headphones with noise cancellation.', 'price' => 79.99, 'stock' => 50],
            ['category_id' => 1, 'name' => 'Smartphone Stand', 'slug' => 'smartphone-stand', 'description' => 'Adjustable aluminum smartphone stand.', 'price' => 19.99, 'stock' => 100],
            ['category_id' => 1, 'name' => 'USB-C Hub', 'slug' => 'usb-c-hub', 'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader.', 'price' => 45.00, 'stock' => 30],
            ['category_id' => 2, 'name' => 'Cotton T-Shirt', 'slug' => 'cotton-t-shirt', 'description' => 'Premium 100% cotton t-shirt.', 'price' => 24.99, 'stock' => 200],
            ['category_id' => 2, 'name' => 'Denim Jeans', 'slug' => 'denim-jeans', 'description' => 'Classic fit denim jeans.', 'price' => 59.99, 'stock' => 80],
            ['category_id' => 3, 'name' => 'Laravel Up & Running', 'slug' => 'laravel-up-running', 'description' => 'A comprehensive guide to the Laravel framework.', 'price' => 39.99, 'stock' => 40],
            ['category_id' => 3, 'name' => 'Clean Code', 'slug' => 'clean-code', 'description' => 'A handbook of agile software craftsmanship.', 'price' => 34.99, 'stock' => 60],
            ['category_id' => 4, 'name' => 'Garden Tool Set', 'slug' => 'garden-tool-set', 'description' => '5-piece stainless steel garden tool set.', 'price' => 29.99, 'stock' => 45],
            ['category_id' => 5, 'name' => 'Yoga Mat', 'slug' => 'yoga-mat', 'description' => 'Non-slip exercise yoga mat, 6mm thick.', 'price' => 22.99, 'stock' => 120],
            ['category_id' => 5, 'name' => 'Resistance Bands Set', 'slug' => 'resistance-bands-set', 'description' => 'Set of 5 resistance bands with varying tension.', 'price' => 15.99, 'stock' => 90],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
