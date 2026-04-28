<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Rayon;
use App\Models\Rombel;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['siswa.rayon', 'siswa.rombel', 'details.barang', 'dendas']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->sampai);
        }

        // Filter siswa
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Filter rayon
        if ($request->filled('rayon_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('rayon_id', $request->rayon_id);
            });
        }

        // Filter rombel
        if ($request->filled('rombel_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('rombel_id', $request->rombel_id);
            });
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();

        // Summary counts
        $summary = [
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'kembali'  => Peminjaman::where('status', 'kembali')->count(),
            'hilang'   => Peminjaman::where('status', 'hilang')->count(),
            'rusak'    => Peminjaman::where('status', 'rusak')->count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
        ];

        $siswas  = User::where('role', 'siswa')->get();
        $rayons  = Rayon::all();
        $rombels = Rombel::all();

        return view('admin.laporan', compact('peminjamans', 'summary', 'siswas', 'rayons', 'rombels'));
    }
}