<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'pico',
            'email' => 'castillo.cesar.pico@gmail.com',
            'password' => '$2y$10$TzNxX7S1.RF.BF2WSUzHkO1DaxRp.L7HLXuM1.uu0BHHvY35k48Ii',
            'is_active' => true,
            'role_id' => '1',
            'image' => 'picoDev.png',
        ]);
        DB::table('users')->insert([
            'name' => 'Manuel',
            'email' => 'manuel@trackerdev.com.ar',
            'password' => '$2y$10$jQsIqrDGWv5alTQMb5XU.OfvzfeA7Lo5q8gpb0cjBnrnqSO4D7./O',
            'is_active' => true,
            'role_id' => '2',
            'image' => 'manuDev.png',
        ]);
    }
}
