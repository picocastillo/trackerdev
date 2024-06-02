<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([//1
            'seniority' => 'senior',
            'weight' => 2,
        ]);
        DB::table('roles')->insert([//2
            'weight' => 1,
            'seniority' => 'semi-senior',
        ]);
        DB::table('roles')->insert([//3
            'weight' => 0.8,
            'seniority' => 'junior',
        ]);
        DB::table('roles')->insert([//4
            'weight' => 1,
            'seniority' => 'stackeholder',
        ]);
        DB::table('roles')->insert([//5
            'weight' => 1,
            'seniority' => 'professional',
        ]);
    }
}
