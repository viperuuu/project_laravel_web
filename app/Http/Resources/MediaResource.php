<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'type'         => $this->type,
            'genre'        => $this->genre,
            'creator'      => $this->creator,
            'release_year' => $this->release_year,
            'description'  => $this->description,
            // Jika diakses melalui relasi pivot koleksi pengguna
            'collection_status' => $this->whenPivotLoaded('media_user', function () {
                return $this->pivot->status;
            }),
            'added_at' => $this->whenPivotLoaded('media_user', function () {
                return $this->pivot->created_at?->toIso8601String();
            }),
            // Eager loaded kolektor / penggemar (whenLoaded)
            'collected_by_users' => UserResource::collection($this->whenLoaded('users')),
            'created_at'   => $this->created_at?->toIso8601String(),
            'updated_at'   => $this->updated_at?->toIso8601String(),
        ];
    }
}
