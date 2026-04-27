<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $fillable = [
        'peminjaman_id', 'jenis', 'jumlah_hari', 'total', 'status_bayar'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}