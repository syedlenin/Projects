<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
 public function definition(): array
{
    $categories = ['Electronics', 'Clothing', 'Books', 'Home & Kitchen', 'Sports', 'Beauty'];

    return [
        'name'        => $this->faker->words(3, true),
        'description' => $this->faker->paragraph(2),
        'price'       => $this->faker->randomFloat(2, 50, 5000),
        'stock'       => $this->faker->numberBetween(5, 100),
        'category'    => $this->faker->randomElement($categories),
        'image'       => null,
        'is_active'   => true,
    ];
}
}
