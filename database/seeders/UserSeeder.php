<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 200; $i++) {

            $firstName = $faker->firstName;
            $lastName  = $faker->lastName;

            $user = User::create([
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'username'   => strtolower($firstName . $i),
                'email'      => $faker->unique()->safeEmail,
                'password'   => Hash::make('admin'),
                'status'     => $faker->randomElement(['active', 'suspend', 'banned']),
            ]);

            $user->assignRole('guest');
        }
    }
}
