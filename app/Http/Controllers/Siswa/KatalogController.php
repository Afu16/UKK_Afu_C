<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KatalogController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('stok_tersedia', '>', 0)
            ->latest()->paginate(12);
        return view('siswa.katalog', compact('barangs'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'barang_id'           => 'required|exists:barangs,id',
            'jumlah'              => 'required|integer|min:1',
            'tgl_kembali_rencana' => 'required|date|after:today',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok_tersedia < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak cukup!']);
        }

        // Cek siswa masih punya peminjaman aktif
        $aktif = Peminjaman::where('siswa_id', auth()->id())
            ->where('status', 'dipinjam')
            ->exists();

        if ($aktif) {
            return back()->with('error', 'Kamu masih memiliki peminjaman aktif. Kembalikan dulu sebelum meminjam lagi.');
        }

        $peminjaman = Peminjaman::create([
            'siswa_id'            => auth()->id(),
            'petugas_id'          => null,
            'tgl_pinjam'          => Carbon::today(),
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'status'              => 'menunggu',
        ]);

        PeminjamanDetail::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id'     => $request->barang_id,
            'jumlah'        => $request->jumlah,
        ]);

        logActivity('REQUEST', 'Siswa ' . auth()->user()->name . ' request pinjam ' . $barang->nama);

        return redirect()->route('siswa.history')->with('success', 'Request peminjaman berhasil! Tunggu konfirmasi petugas.');
    }
}