<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'is_available', 'member_id'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Relasi: satu Book dimiliki oleh satu Member (belongsTo)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
