<?php
namespace Database\Factories;


use App\Models\Buyer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuyerFactory extends Factory
{
    protected $model = Buyer::class;

    public function definition()
    {
        return [
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'contact' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'barangay' => $this->faker->streetName,
            'city' => $this->faker->city,
            'landmark' => $this->faker->secondaryAddress,
            // Add other fields as needed
        ];
    }
}
