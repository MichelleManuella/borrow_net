<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'pengembalian_id',
        'nominal',
        'status_bayar',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class);
    }
}
