<?php

namespace Database\Factories;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->text(),
            'due_date' => Carbon::now()->addDays(rand(-1, -30)),
        ];
    }

    /**
     * Indicate that the user is suspended.
     */
    public function withPriority(Organization $organization): Factory
    {
        return $this->state(function (array $attributes) use ($organization) {
            return [
                'priority_id' => $organization->priorities()->inRandomOrder()->first()->id,
            ];
        });
    }
}
