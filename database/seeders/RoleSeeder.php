<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'seniority' => 'senior',
            'weight' => 2,
        ]);
        DB::table('roles')->insert([
            'weight' => 1,
            'seniority' => 'semi-senior',
        ]);
        DB::table('roles')->insert([
            'weight' => 0.8,
            'seniority' => 'junior',
        ]);
        DB::table('roles')->insert([
            'weight' => 1,
            'seniority' => 'stackeholder',
        ]);
        DB::table('roles')->insert([
            'weight' => 1,
            'seniority' => 'professional',
        ]);
    }
}
