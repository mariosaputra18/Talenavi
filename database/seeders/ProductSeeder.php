<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            ['category' => 'electronics', 'title' => 'Wireless Mouse', 'description' => 'Wireless mouse with ergonomic design.', 'price' => 150000, 'stock' => 25],
            ['category' => 'electronics', 'title' => 'Mechanical Keyboard', 'description' => 'Mechanical keyboard for everyday productivity.', 'price' => 450000, 'stock' => 15],
            ['category' => 'electronics', 'title' => 'USB-C Hub', 'description' => 'Multi-port USB-C hub.', 'price' => 250000, 'stock' => 20],
            ['category' => 'electronics', 'title' => 'Headphones', 'description' => 'Over-ear stereo headphones.', 'price' => 350000, 'stock' => 18],
            ['category' => 'electronics', 'title' => 'Webcam HD', 'description' => 'HD webcam for meetings and streaming.', 'price' => 500000, 'stock' => 10],
            ['category' => 'electronics', 'title' => 'Portable SSD 1TB', 'description' => 'Portable solid state drive with 1TB capacity.', 'price' => 1200000, 'stock' => 8],

            // Fashion
            ['category' => 'fashion', 'title' => 'Basic T-Shirt', 'description' => 'Comfortable cotton basic t-shirt.', 'price' => 85000, 'stock' => 40],
            ['category' => 'fashion', 'title' => 'Hoodie', 'description' => 'Casual hoodie for daily wear.', 'price' => 220000, 'stock' => 20],
            ['category' => 'fashion', 'title' => 'Denim Jeans', 'description' => 'Classic denim jeans.', 'price' => 275000, 'stock' => 15],
            ['category' => 'fashion', 'title' => 'Sneakers', 'description' => 'Casual sneakers for everyday use.', 'price' => 500000, 'stock' => 12],
            ['category' => 'fashion', 'title' => 'Canvas Backpack', 'description' => 'Canvas backpack for school and work.', 'price' => 180000, 'stock' => 18],
            ['category' => 'fashion', 'title' => 'Baseball Cap', 'description' => 'Adjustable baseball cap.', 'price' => 95000, 'stock' => 25],

            // Home & Living
            ['category' => 'home-living', 'title' => 'Desk Lamp', 'description' => 'LED desk lamp for studying and working.', 'price' => 175000, 'stock' => 20],
            ['category' => 'home-living', 'title' => 'Ceramic Mug', 'description' => 'Ceramic mug for hot and cold beverages.', 'price' => 45000, 'stock' => 30],
            ['category' => 'home-living', 'title' => 'Pillow', 'description' => 'Soft pillow for comfortable sleep.', 'price' => 120000, 'stock' => 20],
            ['category' => 'home-living', 'title' => 'Storage Box', 'description' => 'Multi-purpose storage box.', 'price' => 80000, 'stock' => 25],
            ['category' => 'home-living', 'title' => 'Wall Clock', 'description' => 'Minimalist wall clock.', 'price' => 150000, 'stock' => 15],
            ['category' => 'home-living', 'title' => 'Water Bottle', 'description' => 'Reusable water bottle.', 'price' => 65000, 'stock' => 30],

            // Books
            ['category' => 'books', 'title' => 'Clean Code', 'description' => 'A guide to writing maintainable software.', 'price' => 320000, 'stock' => 10],
            ['category' => 'books', 'title' => 'Atomic Habits', 'description' => 'A book about building good habits.', 'price' => 120000, 'stock' => 15],
            ['category' => 'books', 'title' => 'The Pragmatic Programmer', 'description' => 'Practical software development principles.', 'price' => 350000, 'stock' => 8],
            ['category' => 'books', 'title' => 'Deep Work', 'description' => 'A guide to focused and productive work.', 'price' => 140000, 'stock' => 12],
            ['category' => 'books', 'title' => 'Introduction to Algorithms', 'description' => 'Fundamental algorithms and problem solving.', 'price' => 550000, 'stock' => 6],
            ['category' => 'books', 'title' => 'Design Patterns', 'description' => 'Reusable solutions for software design.', 'price' => 400000, 'stock' => 7],

            // Sports
            ['category' => 'sports', 'title' => 'Yoga Mat', 'description' => 'Non-slip yoga and exercise mat.', 'price' => 200000, 'stock' => 20],
            ['category' => 'sports', 'title' => 'Dumbbell Set', 'description' => 'Dumbbell set for home workouts.', 'price' => 350000, 'stock' => 12],
            ['category' => 'sports', 'title' => 'Resistance Bands', 'description' => 'Resistance bands for strength training.', 'price' => 100000, 'stock' => 25],
            ['category' => 'sports', 'title' => 'Jump Rope', 'description' => 'Jump rope for cardio exercise.', 'price' => 75000, 'stock' => 30],
            ['category' => 'sports', 'title' => 'Basketball', 'description' => 'Standard basketball for indoor and outdoor play.', 'price' => 250000, 'stock' => 10],
            ['category' => 'sports', 'title' => 'Gym Gloves', 'description' => 'Workout gloves with grip support.', 'price' => 90000, 'stock' => 18],
        ];

        foreach ($products as $product) {
            $category = Category::where('slug', $product['category'])->firstOrFail();

            Product::updateOrCreate(
                ['title' => $product['title']],
                [
                    'category_id' => $category->id,
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'image_url' => null,
                ]
            );
        }
    }
}