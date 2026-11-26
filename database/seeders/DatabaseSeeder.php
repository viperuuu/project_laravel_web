<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Demo FlipPlay (Modul 4)
            MediaSeeder::class,
            UserSeeder::class,

            // Codelab (Modul 4)
            MemberSeeder::class,
        ]);
    }
}
