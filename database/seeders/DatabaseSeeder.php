<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database khusus entitas FlipPlay Demo Modul 4.
     */
    public function run(): void
    {
        $this->call([
            MediaSeeder::class,
            UserSeeder::class,
        ]);
    }
}
