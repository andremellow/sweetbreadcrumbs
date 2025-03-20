<?php

namespace Database\Factories;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invite>
 */
class InviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'token' => fake()->uuid(),
            'sent_at' => Carbon::now(),
        ];
    }

    /**
     * Indicate that the user is suspended.
     */
    public function withRole(Organization $organization): Factory
    {
        return $this->state(function (array $attributes) use ($organization) {
            return [
                'role_id' => $organization->roles()->inRandomOrder()->first()->id,
            ];
        });
    }
}
