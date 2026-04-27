<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Riwayat Peminjaman</h2>
        <a href="{{ route('siswa.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Barang</th>
                        <th class="p-3 text-left">Tgl Pinjam</th>
                        <th class="p-3 text-left">Tgl Kembali</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                    <tr class="border-t">
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
                                {{ $p->status == 'menunggu'  ? 'bg-yellow-100 text-yellow-600' :
                                   ($p->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' :
                                   ($p->status == 'kembali'  ? 'bg-green-100 text-green-600' :
                                   ($p->status == 'hilang'   ? 'bg-red-100 text-red-600' :
                                    'bg-orange-100 text-orange-600'))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($p->denda)
                                <span class="text-red-500 font-semibold">
                                    Rp {{ number_format($p->denda->total, 0, ',', '.') }}
                                </span>
                                <span class="text-xs ml-1
                                    {{ $p->denda->status_bayar == 'lunas' ? 'text-green-500' : 'text-red-400' }}">
                                    ({{ $p->denda->status_bayar }})
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-3 text-center text-gray-400">Belum ada riwayat peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $peminjamans->links() }}</div>
        </div>
    </div>
</x-app-layout>