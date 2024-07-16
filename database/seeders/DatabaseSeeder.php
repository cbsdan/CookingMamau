<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Discount;
use App\Models\BakedGood;
use App\Models\Ingredient;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\AvailableSchedule;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create(); // Create Faker instance

        //BakedGoods
        BakedGood::factory()->count(10)->create();
        $bakedGoods = BakedGood::all();

        //Ingredients
        foreach ($bakedGoods as $bakedGood) {
            $numIngredients = rand(1, 5);

            for ($i = 0; $i < $numIngredients; $i++) {
                Ingredient::create([
                    'name' => $faker->word,
                    'unit' => $faker->randomElement(['g', 'kg', 'ml', 'l', 'tsp', 'tbsp', 'cup']),
                    'image_path' => null, // Set image path to null for now
                ]);
            }
        }

        //Discount
        Discount::factory()->count(10)->create();

        //Available Schedule
        $schedules = [
            '2024-03-01 08:00:00',
            '2024-03-04 08:00:00',
            '2024-03-08 08:00:00',
            '2024-03-15 08:00:00',
            '2024-03-26 08:00:00',
            '2024-04-01 08:00:00',
            '2024-04-05 09:30:00',
            '2024-04-06 11:00:00',
            '2024-04-08 11:00:00',
        ];

        foreach ($schedules as $schedule) {
            AvailableSchedule::create([
                'schedule' => $schedule,
            ]);
        }

         // Check if the admin user already exists
         $adminExists = User::where('email', 'cookingmamau@gmail.com')->exists();

         if (!$adminExists) {
             User::create([
                 'email' => 'cookingmamau@gmail.com',
                 'password' => Hash::make('12345678'),
                 'is_admin' => true,
                 'email_verified_at' => Carbon::now() // Set email_verified_at to current datetime
             ]);
        }

        // User and Buyer
        for ($i = 0; $i < 9; $i++) {
            $user = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('12345678'),
                'is_admin' => false,
                'email_verified_at' => Carbon::now() // Set email_verified_at to current datetime
            ]);

            Buyer::create([
                'fname' => $faker->firstName,
                'lname' => $faker->lastName,
                'contact' => $faker->phoneNumber,
                'address' => $faker->streetName,
                'barangay' => $faker->streetName,
                'city' => $faker->city,
                'landmark' => $faker->word,
                'id_user' => $user->id,
            ]);
        }
    }
}
