<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Demo 1
        $user1 = User::firstOrCreate(
            ['email' => 'vip_user@flipplay.id'],
            [
                'name'     => 'Akun VIP FlipPlay',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Buat User Demo 2
        $user2 = User::firstOrCreate(
            ['email' => 'otaku_reader@flipplay.id'],
            [
                'name'     => 'Otaku Reader',
                'password' => Hash::make('password123'),
            ]
        );

        // Pasangkan koleksi awal (attach Many-to-Many)
        $hutanCahaya = Media::where('title', 'Hutan Cahaya')->first();
        $petaPulau   = Media::where('title', 'Peta Pulau Rahasia')->first();

        if ($hutanCahaya && !$user1->media()->where('media_id', $hutanCahaya->id)->exists()) {
            $user1->media()->attach($hutanCahaya->id, ['status' => 'watching']);
        }

        if ($petaPulau && !$user1->media()->where('media_id', $petaPulau->id)->exists()) {
            $user1->media()->attach($petaPulau->id, ['status' => 'reading']);
        }
    }
}
