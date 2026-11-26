<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name'];

    /**
     * Relasi: satu Member bisa meminjam banyak Book (hasMany)
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
