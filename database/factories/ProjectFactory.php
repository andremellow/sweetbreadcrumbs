<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company()
        ];
    }

    /**
 * Indicate that the user is suspended.
 */
public function withPriority(Organization $organization ): Factory
{
    return $this->state(function (array $attributes) use ($organization) {
        return [
            'priority_id' => $organization->priorities()->inRandomOrder()->first()->id,
        ];
    });
}
}
