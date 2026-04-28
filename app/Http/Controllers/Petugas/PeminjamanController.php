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

    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        $jumlah_hilang = $request->jumlah_hilang ?? 0;
        $jumlah_rusak  = $request->jumlah_rusak  ?? 0;
        $total_item    = $peminjaman->details->sum('jumlah');

        $tgl_rencana = $peminjaman->tgl_kembali_rencana;
        $tgl_aktual  = Carbon::today();

        // Tentukan status akhir
        if ($jumlah_hilang > 0 && $jumlah_hilang >= $total_item) {
            $status = 'hilang';
        } elseif ($jumlah_rusak > 0 && $jumlah_rusak >= $total_item) {
            $status = 'rusak';
        } else {
            $status = 'kembali';
        }

        $peminjaman->update([
            'status'             => $status,
            'tgl_kembali_aktual' => $tgl_aktual,
        ]);

        // Kembalikan stok yang kembali normal
        $jumlah_bermasalah = $jumlah_hilang + $jumlah_rusak;
        $jumlah_normal     = $total_item - $jumlah_bermasalah;
        foreach ($peminjaman->details as $detail) {
            if ($jumlah_normal > 0) {
                $detail->barang->increment('stok_tersedia', min($jumlah_normal, $detail->jumlah));
            }
        }

        // Denda telat
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

        // Denda hilang
        if ($jumlah_hilang > 0) {
            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'jenis'         => 'hilang',
                'jumlah_hari'   => 0,
                'total'         => 50000 * $jumlah_hilang,
                'status_bayar'  => 'belum',
            ]);
        }

        // Denda rusak
        if ($jumlah_rusak > 0) {
            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'jenis'         => 'rusak',
                'jumlah_hari'   => 0,
                'total'         => 25000 * $jumlah_rusak,
                'status_bayar'  => 'belum',
            ]);
        }

        logActivity('KEMBALI', 'Peminjaman ID ' . $id . ' dikembalikan. Hilang: ' . $jumlah_hilang . ', Rusak: ' . $jumlah_rusak);

        return redirect()->route('petugas.pengembalian.index')->with('success', 'Pengembalian berhasil dicatat!');
    }
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        logActivity('TOLAK', 'Peminjaman ID ' . $id . ' ditolak oleh ' . auth()->user()->name);

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman ditolak!');
    }
    public function pengembalian()
    {
        $peminjamans = Peminjaman::with(['siswa','details.barang'])
           ->where('status', 'dipinjam')
           ->latest()->paginate(10);
           return view('petugas.pengembalian', compact('peminjamans'));
    }
    public function approve($id)
    {
        $peminjaman = Peminjaman::with('details.barang')->findOrFail($id);

        $peminjaman->update([
            'petugas_id' => auth()->id(),
            'status'     => 'dipinjam',
        ]);

        // Kurangi stok
        foreach ($peminjaman->details as $detail) {
            $detail->barang->decrement('stok_tersedia', $detail->jumlah);
        }

        logActivity('APPROVE', 'Peminjaman ID ' . $id . ' disetujui oleh ' . auth()->user()->name);

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman disetujui!');
    }
}