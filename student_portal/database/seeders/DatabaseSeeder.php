<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::create(['name' => 'Super Admin 3', 'firstname'=>'Admin003','lastname'=>'Admin003', 'email' => 'admin003@promplanner.com', 'account_status' => 1, 'password' => bcrypt('admin003$')]);

        //  \App\Models\User::factory(20)->create();
        //  \App\Models\School::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => fake()->name(),
        //     'firstname' => fake()->firstName(),
        //     'lastname' => fake()->lastName(),
        //     'email' => fake()->unique()->safeEmail(),
        //     'role' => 'localadmin',
        //     'phonenumber' => fake()->phoneNumber(),
        //     'country' => fake()->country(),
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        // \App\Models\User::factory()->create([
        //     'name' => fake()->name(),
        //     'firstname' => fake()->firstName(),
        //     'lastname' => fake()->lastName(),
        //     'email' => fake()->unique()->safeEmail(),
        //     'role' => 'localadmin',
        //     'country' => fake()->country(),
        //     'phonenumber' => fake()->phoneNumber(),
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);
    }
}