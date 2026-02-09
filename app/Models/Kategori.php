<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    /**
     * 1 kategori punya banyak alat
     */
    public function alat()
    {
        return $this->hasMany(Alat::class);
    }
}
