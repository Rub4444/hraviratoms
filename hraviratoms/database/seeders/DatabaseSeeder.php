<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\InvitationTemplateSeeder; // ← ПРАВИЛЬНЫЙ namespace

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InvitationTemplateSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
