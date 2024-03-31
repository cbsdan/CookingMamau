<?php
// DiscountFactory.php
namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition()
    {
        return [
            'discount_code' => strtoupper($this->faker->regexify('[A-Z0-9]{5,15}')),
            'percent' => $this->faker->numberBetween(5, 50),
            'max_number_buyer' => $this->faker->numberBetween(1, 100),
            'min_order_price' => $this->faker->numberBetween(50, 200),
            'is_one_time_use' => $this->faker->boolean,
            'discount_start' => $this->faker->dateTimeBetween('now', '+1 week'),
            'discount_end' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'image_path' => null,
            'max_discount_amount' => $this->faker->numberBetween(10, 1000),
        ];
    }
}
