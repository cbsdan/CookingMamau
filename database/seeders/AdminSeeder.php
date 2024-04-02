<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Check if the admin user already exists
         $adminExists = User::where('email', 'cookingmamau@gmail.com')->exists();

         if (!$adminExists) {
            User::create([
                'email' => 'cookingmamau@gmail.com',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
                'email_verified_at' => Carbon::now(), // Set email_verified_at to current datetime
            ]);
        }
    }
}
