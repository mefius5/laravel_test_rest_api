<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => '',
            'name' => fake()->name(),
            'price' => fake()->numerify('##.##'),
            'description' => fake()->text(400),
            'active' => fake()->boolean(70)
        ];
    }
}
