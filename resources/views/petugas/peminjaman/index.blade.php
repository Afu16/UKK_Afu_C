<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Transaksi Peminjaman</h2>
        <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Daftar Peminjaman</h3>
                <a href="{{ route('petugas.peminjaman.create') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Peminjaman</a>
                   
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Siswa</th>
                        <th class="p-3 text-left">Barang</th>
                        <th class="p-3 text-left">Tgl Pinjam</th>
                        <th class="p-3 text-left">Tgl Kembali</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                    <tr class="border-t">
                        <td class="p-3">{{ $p->siswa->name ?? '-' }}</td>
                        <td class="p-3">
                            @foreach($p->details as $d)
                                {{ $d->barang->nama ?? '-' }} ({{ $d->jumlah }})
                            @endforeach
                        </td>
                        <td class="p-3">{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td class="p-3">
                            {{ $p->tgl_kembali_rencana->format('d/m/Y') }}
                            @if($p->status === 'dipinjam' && now()->gt($p->tgl_kembali_rencana))
                                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded ml-1">TERLAMBAT</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $p->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' :
                                ($p->status == 'kembali' ? 'bg-green-100 text-green-600' :
                                ($p->status == 'hilang'  ? 'bg-red-100 text-red-600' :
                                ($p->status == 'rusak'   ? 'bg-orange-100 text-orange-600' :
                                ($p->status == 'ditolak'? 'bg-red-400 text-red-950':
                                    'bg-yellow-100 text-yellow-600')))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                            @if($p->denda)
                                <div class="mt-1 flex flex-col gap-0.5">
                                    @foreach($p->denda as $d)
                                        <span class="text-xs
                                            {{ $d->jenis == 'hilang' ? 'text-red-500' :
                                            ($d->jenis == 'rusak' ? 'text-orange-500' : 'text-yellow-600') }}">
                                            ⚠️ {{ ucfirst($d->jenis) }}
                                            Rp{{ number_format($d->total, 0, ',', '.') }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
<td class="p-3">
    @if($p->status === 'menunggu')
    <div class="flex gap-1">
        <form method="POST" action="{{ route('petugas.peminjaman.approve', $p->id) }}">
            @csrf
            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                ✓ Approve
            </button>
        </form>
        <form method="POST" action="{{ route('petugas.peminjaman.tolak', $p->id) }}"
              onsubmit="return confirm('Tolak peminjaman ini?')">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                ✗ Tolak
            </button>
        </form>
    </div>
    @elseif($p->status === 'dipinjam')
        <span class="text-blue-400 text-xs">Sedang dipinjam</span>
    @elseif($p->status === 'ditolak')
        <span class="text-red-400 text-xs">Ditolak</span>
    @else
        <span class="text-gray-400 text-xs">Selesai</span>
    @endif
</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-3 text-center text-gray-400">Belum ada peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $peminjamans->links() }}</div>
        </div>
    </div>
</x-app-layout>