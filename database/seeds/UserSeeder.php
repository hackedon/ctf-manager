<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'display_name' => 'CTF Admin',
            'username' => 'ctf_admin',
            'password' => Hash::make('password'),
            'avatar' => 'admin.png',
            'role' => 'ADMIN'
        ]);

        User::create([
            'display_name' => 'HackedON',
            'username' => 'hackedon',
            'password' => Hash::make('password'),
            'avatar' => 'hackedon.png'
        ]);

        User::create([
            'display_name' => 'CyberBotNets',
            'username' => 'cyberbots',
            'password' => Hash::make('password'),
            'avatar' => 'cyberbots.png'
        ]);

        User::create([
            'display_name' => 'Anon',
            'username' => 'anon',
            'password' => Hash::make('password'),
            'avatar' => 'anon.png'
        ]);
    }
}
