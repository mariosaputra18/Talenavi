<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'electronics', 'name' => 'Electronics'],
            ['slug' => 'fashion', 'name' => 'Fashion'],
            ['slug' => 'home-living', 'name' => 'Home & Living'],
            ['slug' => 'books', 'name' => 'Books'],
            ['slug' => 'sports', 'name' => 'Sports'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}