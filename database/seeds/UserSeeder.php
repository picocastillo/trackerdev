<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([//1
            'name' => 'pico',
            'email' => 'castillo.cesar.pico@gmail.com',
            'password' => '$2y$10$TzNxX7S1.RF.BF2WSUzHkO1DaxRp.L7HLXuM1.uu0BHHvY35k48Ii',
            'is_active' => true,
            'role_id' => '1',
            'image' => 'pico_profile.jpg'
        ]);
        DB::table('users')->insert([//1
            'name' => 'Manuel',
            'email' => 'manuel@trackerdev.com.ar',
            'password' => '$2y$10$jQsIqrDGWv5alTQMb5XU.OfvzfeA7Lo5q8gpb0cjBnrnqSO4D7./O',
            'is_active' => true,
            'role_id' => '2',
            'image' => 'pico_profile.jpg'
        ]);
        // DB::table('users')->insert([//2
        //     'name' => 'milton',
        //     'email' => 'milton@trackerdev.com.ar',
        //     'is_active' => true,
        //     'password' => '$2y$10$2CIffX3LQQUAdduc9cGQG.U35hIU32WUyiWiYK7dRuvBUe5OFm.py',//1000ton_!
        //     'role_id' => '3',
        //     'image' => 'milton_profile.jpg'
        // ]);
        // DB::table('users')->insert([//3
        //     'name' => 'showtravelers',
        //     'is_active' => true,
        //     'email' => 'showtravelers@gmail.com',
        //     'password' => '$2y$10$FVEb4LirQskpSD5NegTLIeeokVruAPf6R3QWw4V5biTs9TCvmvTYa',
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//4
        //     'name' => 'tdev',
        //     'email' => 'info@rubrica.com',
        //     'password' => '$2y$10$UOUXLr8zYuH9nchlzrBr3umF9ruulaQKnD/g9/BBcLuae2crYlAkm',//!_rubr1c4_!
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//5
        //     'name' => 'Prego',
        //     'email' => 'admin@prego.com.ar',
        //     'password' => '$2y$10$F8hdg7tM43fBPXbimyXfW.H//tEvnjl1OvHiHKrnd7TAmmjOgfYgy',//_!pr3g0!_
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//6
        //     'name' => 'Ivan',
        //     'email' => 'ivan@trackerdev.com.ar',
        //     'password' => '$2y$10$fphblUaxBRX2O5j0xPq1MePZs62TaAMQbhvkH6t9RWkGKOU01ZXmm',//!1v4n!*$
        //     'is_active' => true,
        //     'role_id' => '3',
        // ]);
        // DB::table('users')->insert([//7
        //     'name' => 'Jorge',
        //     'email' => 'jorge@gmail.com',
        //     'password' => '$2y$10$JQNS2Msrw5uX08zKUd2WWOzLat.YvdJHPC9u0Y2X/gtoHsXdlwHQC',//l1t0r4l159
        //     'is_active' => false,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//8
        //     'name' => 'gonza',
        //     'is_active' => true,
        //     'email' => 'gonza@ojitosrojos.com',
        //     'password' => '$2y$10$C3Xol2lL.Bd059Wb3agBv.r3KdTaCOSsV5.whgS42xDIGrtojQ69u',//oj1t0sr0j02!*
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//9
        //     'name' => 'migue',
        //     'email' => 'migue@trackerdev.com',
        //     'password' => '$2y$10$C3Xol2lL.Bd059Wb3agBv.r3KdTaCOSsV5.whgS42xDIGrtojQ69u',//sabale27_!
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//10
        //     'name' => 'caco',
        //     'email' => 'caco@trackerdev.com',
        //     'password' => '$2y$10$AQnFTBZyabTs3BB51XUliO7IzhV/GO962367AxslTLC1gHgXOryKe',//*c4c0!-
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//11
        //     'name' => 'caco',
        //     'email' => 'info@famacia.com',
        //     'password' => '$2y$10$Uqmx6VirKoCMJu0u3aCBdOgn3wwWNCPX0YGKSt496EGGMXrSpR8hm',//bl4nc*#
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//12
        //     'name' => 'rony',
        //     'email' => 'rony@trackerdev.com.ar',
        //     'password' => '$2y$10$DwrvXlpdKDW3X8mlu0aa5ej7kWNTjHFmQDdjqHVfvNChhg1IpYrYW',//r0ny*4#
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//13
        //     'name' => 'masses',
        //     'email' => 'info@masses.com.ar',
        //     'password' => '$2y$10$DwrvXlpdKDW3X8mlu0aa5ej7kWNTjHFmQDdjqHVfvNChhg1IpYrYW',//m2ss3s3!
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//2
        //     'name' => 'augusto',
        //     'email' => 'augusto@trackerdev.com.ar',
        //     'is_active' => true,
        //     'password' => '$2y$10$u95YYjZWdq9PZbS6Sy.DQe2JE0DfBTcG96E27Y/vceDFFIaKasEri',//-gugui_$%&
        //     'role_id' => '5',
        //     // 'image' => 'gugui_profile.jpg'
        // ]);
        // DB::table('users')->insert([//2
        //     'name' => 'seba',
        //     'email' => 'seba@trackerdev.com.ar',
        //     'is_active' => true,
        //     'password' => '$2y$10$6.Eb/w/3mN48ktsRmsbpgOsp.r951XHaiR5f9pzTUfT1OtVaRbqJO',//-sebA_!%/&
        //     'role_id' => '5',
        //     // 'image' => 'gugui_profile.jpg'
        // ]);
        // DB::table('users')->insert([//12
        //     'name' => 'masses',
        //     'email' => 'massescontent.com',
        //     'password' => '$2y$10$xgS9IWDEyvWM1kXpD4kodOHd.OpLZqsCZ.GFQuqAkG3N/E5b.86hG',//M4ss3s4#$%&
        //     'is_active' => true,
        //     'role_id' => '4',
        // ]);
        // DB::table('users')->insert([//2
        //     'name' => 'carlos',
        //     'email' => 'carlos@trackerdev.com.ar',
        //     'is_active' => true,
        //     'password' => '$2y$10$dv5CZM.ViVfLxdeINdp3tOhNyMjBUDtkqE4f/bGFTCT86jS7VpwO.',//C4rl0S&&/
        //     'role_id' => '3',
        //     'image' => null
        // ]);
        // DB::table('users')->insert([//2
        //     'name' => 'carlos',
        //     'email' => 'carlos@trackerdev.com.ar',
        //     'is_active' => true,
        //     'password' => '$2y$10$vkrLUj8In.JSjxV8uix89udmtRgu2S8eRFoxM9LnlIuHQjgKzQm3m',//c4rl0s_!#--
        //     'role_id' => '3',
        //     'image' => null
        // ]);
    }
}
