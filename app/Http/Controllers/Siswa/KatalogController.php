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
    public function index(Request $request)
    {
        $kategori = $request->kategori;
        $barang   = $request->barang_id ? \App\Models\Barang::find($request->barang_id) : null;

        $kategoris = \App\Models\Barang::where('stok_tersedia', '>', 0)
            ->distinct()->pluck('kategori');

        $barangs = null;
        if ($kategori) {
            $barangs = \App\Models\Barang::where('kategori', $kategori)
                ->where('stok_tersedia', '>', 0)->get();
        }

        return view('siswa.katalog', compact('kategoris', 'kategori', 'barangs', 'barang'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'barang_id'           => 'required|exists:barangs,id',
            'tgl_kembali_rencana' => 'required|date|after:today|before_or_equal:' . Carbon::today()->addWeeks(1)->format('Y-m-d'),
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok_tersedia < 1) {
            return back()->with('error', 'Stok tidak tersedia!');
        }

        $aktif = Peminjaman::where('siswa_id', auth()->id())
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->exists();

        if ($aktif) {
            return back()->with('error', 'Kamu masih memiliki peminjaman aktif atau menunggu.');
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
            'jumlah'        => 1,
        ]);

        logActivity('REQUEST', 'Siswa ' . auth()->user()->name . ' request pinjam ' . $barang->nama);

        return redirect()->route('siswa.history')->with('success', 'Request berhasil! Batas kembali: ' . \Carbon\Carbon::parse($request->tgl_kembali_rencana)->format('d/m/Y'));
    }
}