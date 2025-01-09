<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GarageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Garage',
            'description' => fake()->paragraph(),
            'is_available' => fake()->boolean(80), // 80% chance of being available
        ];
    }
}
