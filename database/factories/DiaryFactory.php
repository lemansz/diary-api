<?php

namespace Database\Factories;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends Factory<Diary>
 */
class DiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::inRandomOrder()->first()?->id ?? User::factory(),
            'title' => $this->faker->optional(0.8)->passthrough($this->faker->sentence(3)),
            'content' => $this->faker->paragraphs(3, true),
            'entry_date' => $this->faker->date('Y-m-d'),
            'mood' => $this->faker->optional(0.7)->randomElement(['happy', 'sad', 'neutral', 'excited', 'tired']),
            'weather' => $this->faker->optional(0.7)->randomElement(['sunny', 'rainy', 'cloudy', 'windy', 'snowy']),
            'is_favourite' => $this->faker->boolean(20)
        ];
    }
}
