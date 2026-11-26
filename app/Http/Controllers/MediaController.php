<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Menampilkan daftar media dengan fitur pencarian judul dan filter tipe (anime/manga).
     */
    public function index(Request $request)
    {
        $query = Media::query();

        // Fitur pencarian berdasarkan judul (query search)
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->query('title') . '%');
        } elseif ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->query('q') . '%');
        }

        // Fitur filter berdasarkan tipe media (anime / manga)
        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        // Fitur filter genre
        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->query('genre') . '%');
        }

        // Eager load relasi users jika diminta via query param with_users=1
        if ($request->boolean('with_users')) {
            $query->with('users');
        }

        $mediaList = $query->latest()->paginate(10);

        return MediaResource::collection($mediaList);
    }

    /**
     * Menyimpan media baru ke dalam katalog FlipPlay.
     */
    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan detail satu media beserta pengguna yang memasukkannya ke koleksi.
     */
    public function show(Media $medium)
    {
        // Eager load users yang mengoleksi media ini
        return new MediaResource($medium->load('users'));
    }

    /**
     * Memperbarui informasi media.
     */
    public function update(StoreMediaRequest $request, Media $medium)
    {
        $medium->update($request->validated());

        return new MediaResource($medium);
    }

    /**
     * Menghapus media dari katalog FlipPlay.
     */
    public function destroy(Media $medium)
    {
        $title = $medium->title;
        $medium->delete();

        return response()->json([
            'status'  => 'success',
            'message' => "Media '{$title}' berhasil dihapus dari sistem FlipPlay.",
        ], 200);
    }
}
