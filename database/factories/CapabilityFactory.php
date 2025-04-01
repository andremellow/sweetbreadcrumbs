<?php

namespace Database\Factories;

use App\Models\Capability;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CapabilityFactory extends Factory
{
    protected $model = Capability::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'group' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
