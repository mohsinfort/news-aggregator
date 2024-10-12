<?php

namespace Database\Factories;

use App\DataObject\NewsSourceData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => Str::random(10),
            'description' => fake()->sentence(100),
            'url' => fake()->url(),
            'type' => fake()->word(),
            'source' => NewsSourceData::NEW_YORK_TIMES,
            'published_at' => now(),
        ];
    }
}
