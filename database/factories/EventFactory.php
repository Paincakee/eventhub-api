<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Random\RandomException;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @throws RandomException
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement(['Kerstmarkt', 'Muziekfestival', 'Hackathon', 'Workshop', 'Netwerkborrel']).' '.fake()->city();
        $start_date = fake()->dateTime();

        return [
            'title' => $title,
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
