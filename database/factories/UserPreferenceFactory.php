<?php

namespace Database\Factories;

use App\DataObject\NewsSourceData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            "user_id" => $user->id,
            "news_type" => Str::random(5),
            "news_source" => NewsSourceData::NEW_YORK_TIMES,
            "news_author" => fake()->name(),
        ];
    }
}
