<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin One',
            'email' => 'adminone@gmail.com',
            'password' => Hash::make('AdminOne123'),
        ]);

        User::create([
            'name' => 'Admin Two',
            'email' => 'admintwo@gmail.com',
            'password' => Hash::make('AdminTwo123'),
        ]);
    }
}
