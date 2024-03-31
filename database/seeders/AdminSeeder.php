<?php

namespace Database\Seeders;

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
         $adminExists = User::where('email', 'admin@gmail.com')->exists();

         if (!$adminExists) {
             User::create([
                 'email' => 'admin@gmail.com',
                 'password' => Hash::make('12345678'),
                 'is_admin' => true,
             ]);
        }
    }
}
