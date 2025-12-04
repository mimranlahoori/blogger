<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(10, true),
            'excerpt' => $this->faker->paragraph(2),
            'image' => null,
            'status' => $this->faker->randomElement(['draft', 'published', 'published', 'published']),
            'featured' => $this->faker->boolean(20),
            'views' => $this->faker->numberBetween(0, 10000),
            'likes_count' => $this->faker->numberBetween(0, 500),
            'comments_count' => $this->faker->numberBetween(0, 100),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        });
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
                'published_at' => null,
            ];
        });
    }

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'featured' => true,
            ];
        });
    }
}
