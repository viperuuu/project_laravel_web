<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'type',
        'genre',
        'creator',
        'release_year',
        'description',
    ];

    protected $casts = [
        'release_year' => 'integer',
    ];

    /**
     * Relasi Many-to-Many: Satu Media dapat dikoleksi oleh banyak Pengguna (User).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'media_user')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
