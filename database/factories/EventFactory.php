<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        $title = fake()->firstName();
        $start_date = fake()->dateTime();

        return [
            'title' => $title,
            'slug' => strtolower(trim($title)),
            'description' => fake()->text(),
            'start_date' => $start_date,
            'end_date' => $start_date->modify('+5 days'),
            'location' => fake()->address(),
            'max_attendees' => random_int(10, 100),
            'image_url' => fake()->imageUrl(),
            'is_published' => fake()->boolean(),
        ];
    }
}
