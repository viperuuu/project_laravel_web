<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use App\Http\Requests\AddMediaToCollectionRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{
    /**
     * Menampilkan seluruh koleksi media milik seorang pengguna.
     */
    public function index(User $user)
    {
        return new UserResource($user->load('media'));
    }

    /**
     * Custom Action: Menambahkan media ke dalam koleksi pengguna (attach relasi).
     * Disertai validasi pencegahan duplikasi data (Failure Path).
     */
    public function store(AddMediaToCollectionRequest $request, User $user)
    {
        $mediaId = $request->validated('media_id');
        $status  = $request->validated('status', 'plan_to_consume');

        // Cek apakah media sudah ada di dalam koleksi pengguna
        if ($user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Media ini sudah ada di dalam daftar koleksi Anda.',
            ], 409); // 409 Conflict
        }

        // Attach relasi Many-to-Many
        $user->media()->attach($mediaId, [
            'status' => $status,
        ]);

        $media = Media::find($mediaId);

        return response()->json([
            'status'  => 'success',
            'message' => "Media '{$media->title}' berhasil ditambahkan ke koleksi pengguna.",
            'data'    => [
                'user_id'           => $user->id,
                'media_id'          => $media->id,
                'media_title'       => $media->title,
                'collection_status' => $status,
            ],
        ], 201);
    }

    /**
     * Custom Action: Menghapus media dari koleksi pengguna (detach relasi).
     */
    public function destroy(User $user, Media $medium)
    {
        // Pastikan media ada di dalam koleksi user
        if (!$user->media()->where('media_id', $medium->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Media tidak ditemukan di dalam koleksi pengguna ini.',
            ], 404);
        }

        // Detach relasi Many-to-Many
        $user->media()->detach($medium->id);

        return response()->json([
            'status'  => 'success',
            'message' => "Media '{$medium->title}' berhasil dihapus dari koleksi pengguna.",
        ], 200);
    }
}
