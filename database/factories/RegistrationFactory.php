<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'user_id' => User::factory()->create(),
            'event_id' => Event::factory()->create(),
        ];
    }

    /**
     * Returns a registration for a user with account
     * .
     * @return Factory
     */
    public function user(): Factory
    {
        return $this->state(function () {
            return [
                'user_id' => User::factory()->create(),
            ];
        });
    }

    /**
     * Returns a registration for a guest user.
     *
     * @return Factory
     */
    public function guest(): Factory
    {
        return $this->state(function () {
            return [
                'user_id' => null,
            ];
        });
    }
}
