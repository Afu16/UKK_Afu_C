<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class HistoryController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['details.barang', 'denda'])
            ->where('siswa_id', auth()->id())
            ->latest()->paginate(10);

        return view('siswa.history', compact('peminjamans'));
    }
    public function denda()
{
    $dendas = \App\Models\Denda::whereHas('peminjaman', function ($q) {
            $q->where('siswa_id', auth()->id());
        })
        ->with(['peminjaman.details.barang'])
        ->latest()->paginate(10);

    return view('siswa.denda', compact('dendas'));
}
}