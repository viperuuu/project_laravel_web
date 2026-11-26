<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $mediaData = [
            [
                'title'        => 'Hutan Cahaya',
                'type'         => 'anime',
                'genre'        => 'Fantasi, Petualangan',
                'creator'      => 'Kyoto Animation',
                'release_year' => 2024,
                'description'  => 'Perjalanan seorang pengembara misterius mengungkap rahasia hutan mistis yang bercahaya di malam hari.',
            ],
            [
                'title'        => 'Peta Pulau Rahasia',
                'type'         => 'manga',
                'genre'        => 'Petualangan, Misteri',
                'creator'      => 'Eiichiro Oda / Shueisha',
                'release_year' => 2023,
                'description'  => 'Pencarian harta karun purbakala yang tersembunyi di balik peta kuno yang hilang berabad-abad.',
            ],
            [
                'title'        => 'Sousou no Frieren',
                'type'         => 'anime',
                'genre'        => 'Fantasi, Drama',
                'creator'      => 'Madhouse',
                'release_year' => 2023,
                'description'  => 'Kisah kehidupan seorang penyihir elf abadi setelah petualangan mengalahkan raja iblis selesai.',
            ],
            [
                'title'        => 'Chainsaw Man',
                'type'         => 'manga',
                'genre'        => 'Action, Dark Fantasy',
                'creator'      => 'Tatsuki Fujimoto',
                'release_year' => 2018,
                'description'  => 'Denji pemuda miskin yang bergabung dengan iblis gergaji mesin untuk membasmi iblis jahat.',
            ],
            [
                'title'        => 'Cyberpunk: Edgerunners',
                'type'         => 'anime',
                'genre'        => 'Sci-Fi, Cyberpunk',
                'creator'      => 'Studio Trigger',
                'release_year' => 2022,
                'description'  => 'Kisah pemuda jalanan berjuang bertahan hidup di kota masa depan Night City yang kejam dan terobsesi dengan teknologi modifikasi tubuh.',
            ],
        ];

        foreach ($mediaData as $data) {
            Media::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
