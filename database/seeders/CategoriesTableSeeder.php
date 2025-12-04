<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Posts about technology, programming, and software development',
                'is_active' => true,
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Posts about lifestyle, daily living, and personal growth',
                'is_active' => true,
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel experiences, tips, and destination guides',
                'is_active' => true,
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Recipes, cooking tips, and food reviews',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational content, tutorials, and learning resources',
                'is_active' => true,
            ],
            [
                'name' => 'Health',
                'slug' => 'health',
                'description' => 'Health tips, fitness, and wellness advice',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business insights, entrepreneurship, and finance',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Movies, music, games, and entertainment news',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
