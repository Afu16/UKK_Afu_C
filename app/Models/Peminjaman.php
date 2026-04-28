<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $fillable = [
        'siswa_id', 'petugas_id', 'tgl_pinjam',
        'tgl_kembali_rencana', 'tgl_kembali_aktual', 'status'
    ];

    protected $casts = [
        'tgl_pinjam'           => 'date',
        'tgl_kembali_rencana'  => 'date',
        'tgl_kembali_aktual'   => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

    public function dendas()
    {
        return $this->hasMany(Denda::class);
    }
}