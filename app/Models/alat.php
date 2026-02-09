<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'nama_alat',
        'kategori_id',
        'kondisi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    
    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class);
}

}
