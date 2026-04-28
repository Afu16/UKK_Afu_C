<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;

class HistoryController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['details.barang', 'dendas'])
            ->where('siswa_id', auth()->id())
            ->latest()->paginate(10);

        return view('siswa.history', compact('peminjamans'));
    }
    public function denda()
    {
        $dendas = Denda::whereHas('peminjaman', function ($q) {
                $q->where('siswa_id', auth()->id());
            })
            ->with(['peminjaman.details.barang'])
            ->latest()->paginate(10);

        return view('siswa.denda', compact('dendas'));
    }
    public function kartu()
    {
        $user = auth()->user();
        return view('siswa.kartu', compact('user'));
    }
}