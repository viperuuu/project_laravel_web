<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * 1. PENGUJIAN CRUD: Menampilkan daftar media dengan query filter pencarian judul.
     */
    public function test_get_media_list_and_search_by_title(): void
    {
        $response = $this->getJson('/api/media?title=Hutan');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'type', 'genre', 'creator', 'release_year']
                     ]
                 ]);
    }

    /**
     * 2. PENGUJIAN CRUD: Filter media berdasarkan tipe (anime / manga).
     */
    public function test_filter_media_by_type(): void
    {
        $response = $this->getJson('/api/media?type=manga');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $item) {
            $this->assertEquals('manga', $item['type']);
        }
    }

    /**
     * 3. PENGUJIAN CRUD: Tambah media baru (Happy Path - Status 201).
     */
    public function test_store_new_media_success(): void
    {
        $payload = [
            'title'        => 'Solo Leveling: Arise',
            'type'         => 'anime',
            'genre'        => 'Action, Fantasy',
            'creator'      => 'A-1 Pictures',
            'release_year' => 2024,
            'description'  => 'Perjalanan Sung Jin-woo dari hunter terlemah menjadi hunter bayangan terkuat di dunia.',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'Solo Leveling: Arise')
                 ->assertJsonPath('data.type', 'anime');

        $this->assertDatabaseHas('media', [
            'title' => 'Solo Leveling: Arise'
        ]);
    }

    /**
     * 4. PENGUJIAN VALIDASI: Menambahkan media dengan field kosong (Failure Path - Status 422).
     */
    public function test_store_media_validation_failure_empty_title(): void
    {
        $payload = [
            'title' => '',
            'type'  => 'invalid_type',
            'genre' => '',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'type', 'genre']);
    }

    /**
     * 5. PENGUJIAN VALIDASI: Menambahkan media dengan judul duplikat (Failure Path - Status 422).
     */
    public function test_store_media_validation_duplicate_title(): void
    {
        $payload = [
            'title' => 'Hutan Cahaya',
            'type'  => 'anime',
            'genre' => 'Fantasi',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    /**
     * 6. PENGUJIAN CRUD: Detail media beserta pengguna yang mengoleksi (Route Model Binding + Eager Loading).
     */
    public function test_show_media_with_collectors(): void
    {
        $media = Media::where('title', 'Hutan Cahaya')->first();

        $response = $this->getJson("/api/media/{$media->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $media->id)
                 ->assertJsonStructure([
                     'data' => [
                         'id', 'title', 'type', 'collected_by_users'
                     ]
                 ]);
    }

    /**
     * 7. PENGUJIAN CRUD: Update media (Status 200).
     */
    public function test_update_media_success(): void
    {
        $media = Media::first();

        $response = $this->patchJson("/api/media/{$media->id}", [
            'description' => 'Deskripsi berhasil diperbarui via PATCH API.',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.description', 'Deskripsi berhasil diperbarui via PATCH API.');
    }

    /**
     * 8. PENGUJIAN CUSTOM ACTION: Menambahkan media ke koleksi pengguna (Attach - Status 201).
     */
    public function test_add_media_to_user_collection_success(): void
    {
        $user = User::first();
        // Cari media yang belum dikoleksi oleh user ini
        $uncollectedMedia = Media::whereDoesntHave('users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        $response = $this->postJson("/api/users/{$user->id}/collections", [
            'media_id' => $uncollectedMedia->id,
            'status'   => 'watching',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('media_user', [
            'user_id'  => $user->id,
            'media_id' => $uncollectedMedia->id,
        ]);
    }

    /**
     * 9. PENGUJIAN CUSTOM ACTION: Tambah media duplikat ke koleksi pengguna (Failure Path - Status 409 Conflict).
     */
    public function test_add_duplicate_media_to_collection_conflict(): void
    {
        $user = User::first();
        // Ambil media yang sudah ada di koleksi user (diset oleh seeder)
        $collectedMedia = $user->media()->first();

        $response = $this->postJson("/api/users/{$user->id}/collections", [
            'media_id' => $collectedMedia->id,
            'status'   => 'watching',
        ]);

        $response->assertStatus(409)
                 ->assertJsonPath('status', 'error');
    }

    /**
     * 10. PENGUJIAN CUSTOM ACTION: Menampilkan daftar koleksi pengguna (Status 200).
     */
    public function test_get_user_collection(): void
    {
        $user = User::first();

        $response = $this->getJson("/api/users/{$user->id}/collections");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id', 'name', 'email', 'media_collection'
                     ]
                 ]);
    }

    /**
     * 11. PENGUJIAN CUSTOM ACTION: Menghapus media dari koleksi pengguna (Detach - Status 200).
     */
    public function test_remove_media_from_collection_success(): void
    {
        $user = User::first();
        $media = $user->media()->first();

        $response = $this->deleteJson("/api/users/{$user->id}/collections/{$media->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('media_user', [
            'user_id'  => $user->id,
            'media_id' => $media->id,
        ]);
    }

    /**
     * 12. PENGUJIAN CRUD: Hapus media dari sistem FlipPlay (Delete - Status 200).
     */
    public function test_delete_media_success(): void
    {
        $media = Media::create([
            'title' => 'Temporary Media to Delete',
            'type'  => 'anime',
            'genre' => 'Comedy',
        ]);

        $response = $this->deleteJson("/api/media/{$media->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);
    }
}
