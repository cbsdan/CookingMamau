<?php

namespace Database\Factories;

use App\Models\BakedGood;
use Illuminate\Database\Eloquent\Factories\Factory;

class BakedGoodFactory extends Factory
{
    protected $model = BakedGood::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'is_available' => $this->faker->boolean,
            'description' => $this->faker->sentence,
            'weight_gram' => $this->faker->numberBetween(50, 1000),
        ];
    }
}
