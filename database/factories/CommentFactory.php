<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'news_id' => News::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'content' => $this->faker->paragraphs(rand(1, 3), true),
            'approved' => true,
            'is_featured' => $this->faker->boolean(10), // 10% chance of being featured
            'mentions' => null,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function reply()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Comment::factory(),
                'content' => $this->faker->sentences(rand(1, 2), true),
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'approved' => false,
            ];
        });
    }

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
                'approved' => true,
            ];
        });
    }
}
