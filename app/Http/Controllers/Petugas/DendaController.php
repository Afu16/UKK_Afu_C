<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;

class DendaController extends Controller
{
    public function index()
    {
        $dendas = Denda::with(['peminjaman.siswa', 'peminjaman.details.barang'])
            ->latest()->paginate(15);
        return view('petugas.denda', compact('dendas'));
    }

    public function lunas($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->update(['status_bayar' => 'lunas']);

        logActivity('DENDA_LUNAS', 'Denda ID ' . $id . ' ditandai lunas');

        return redirect()->route('petugas.denda.index')->with('success', 'Denda berhasil ditandai lunas!');
    }
}