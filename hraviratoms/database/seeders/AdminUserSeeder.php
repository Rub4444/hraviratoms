<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'isahakyan06@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Ruben15.'), // замени если хочешь
                'is_superadmin' => 1,
            ]
        );
    }
}
