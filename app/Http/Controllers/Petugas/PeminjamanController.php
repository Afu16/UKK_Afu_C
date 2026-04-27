<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Barang;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['siswa', 'details.barang'])
            ->latest()->paginate(10);
        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $siswas  = User::where('role', 'siswa')->get();
        $barangs = Barang::where('stok_tersedia', '>', 0)->get();
        return view('petugas.peminjaman.create', compact('siswas', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'            => 'required|exists:users,id',
            'barang_id'           => 'required|exists:barangs,id',
            'jumlah'              => 'required|integer|min:1',
            'tgl_kembali_rencana' => 'required|date|after:today',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stok_tersedia < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak cukup!'])->withInput();
        }

        // Buat peminjaman
        $peminjaman = Peminjaman::create([
            'siswa_id'            => $request->siswa_id,
            'petugas_id'          => auth()->id(),
            'tgl_pinjam'          => Carbon::today(),
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'status'              => 'dipinjam',
        ]);

        // Detail
        PeminjamanDetail::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id'     => $request->barang_id,
            'jumlah'        => $request->jumlah,
        ]);

        // Kurangi stok
        $barang->decrement('stok_tersedia', $request->jumlah);

        logActivity('PINJAM', 'Siswa ' . $peminjaman->siswa->name . ' meminjam ' . $barang->nama);

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        $tgl_rencana = $peminjaman->tgl_kembali_rencana;
        $tgl_aktual  = Carbon::today();

        $peminjaman->update([
            'status'              => 'kembali',
            'tgl_kembali_aktual'  => $tgl_aktual,
        ]);

        // Kembalikan stok
        foreach ($peminjaman->details as $detail) {
            $detail->barang->increment('stok_tersedia', $detail->jumlah);
        }

        // Cek denda telat
        if ($tgl_aktual->gt($tgl_rencana)) {
            $selisih = $tgl_rencana->diffInDays($tgl_aktual);
            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'jenis'         => 'telat',
                'jumlah_hari'   => $selisih,
                'total'         => $selisih * 1000,
                'status_bayar'  => 'belum',
            ]);
        }

        logActivity('KEMBALI', 'Peminjaman ID ' . $id . ' dikembalikan');

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Pengembalian berhasil!');
    }

    public function laporHilang($id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        $peminjaman->update([
            'status'             => 'hilang',
            'tgl_kembali_aktual' => Carbon::today(),
        ]);

        Denda::create([
            'peminjaman_id' => $peminjaman->id,
            'jenis'         => 'hilang',
            'jumlah_hari'   => 0,
            'total'         => 50000, // nominal denda hilang
            'status_bayar'  => 'belum',
        ]);

        logActivity('HILANG', 'Peminjaman ID ' . $id . ' dilaporkan hilang');

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Laporan hilang dicatat!');
    }

    public function laporRusak($id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        $peminjaman->update([
            'status'             => 'rusak',
            'tgl_kembali_aktual' => Carbon::today(),
        ]);

        Denda::create([
            'peminjaman_id' => $peminjaman->id,
            'jenis'         => 'rusak',
            'jumlah_hari'   => 0,
            'total'         => 25000, // nominal denda rusak
            'status_bayar'  => 'belum',
        ]);

        logActivity('RUSAK', 'Peminjaman ID ' . $id . ' dilaporkan rusak');

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Laporan rusak dicatat!');
    }
}