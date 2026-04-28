<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Pengembalian Barang</h2>
    <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Siswa</th>
                        <th class="p-3 text-left">Barang</th>
                        <th class="p-3 text-left">Tgl Pinjam</th>
                        <th class="p-3 text-left">Tgl Kembali</th>
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
                            @if(now()->gt($p->tgl_kembali_rencana))
                                <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded ml-1">TERLAMBAT</span>
                            @endif
                        </td>
<td class="p-3">
    <form method="POST" action="{{ route('petugas.peminjaman.kembalikan', $p->id) }}"
          onsubmit="return confirm('Konfirmasi pengembalian?')">
        @csrf
        @php $total = $p->details->sum('jumlah') @endphp

        <div class="text-xs text-gray-500 mb-2">Total item: <strong>{{ $total }}</strong></div>

        <div class="flex items-center gap-1 mb-1">
            <span class="text-xs w-20 text-red-500">❌ Hilang:</span>
            <input type="number" name="jumlah_hilang" value="0" min="0" max="{{ $total }}"
                   class="w-14 border rounded px-2 py-1 text-xs text-center">
        </div>

        <div class="flex items-center gap-1 mb-2">
            <span class="text-xs w-20 text-orange-500">⚠️ Rusak:</span>
            <input type="number" name="jumlah_rusak" value="0" min="0" max="{{ $total }}"
                   class="w-14 border rounded px-2 py-1 text-xs text-center">
        </div>

        <button class="w-full bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-xs font-semibold">
            ✅ Proses Pengembalian
        </button>
    </form>
</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-3 text-center text-gray-400">Tidak ada peminjaman aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $peminjamans->links() }}</div>
        </div>
    </div>
</x-app-layout>