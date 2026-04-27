<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Denda Saya</h2>
    <a href="{{ route('siswa.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">

        {{-- Total belum lunas --}}
        @php
            $totalBelum = $dendas->where('status_bayar', 'belum')->sum('total');
        @endphp

        @if($totalBelum > 0)
        <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-6 text-center">
            <p class="text-red-600 font-semibold text-lg">
                ⚠️ Total Denda Belum Lunas:
                Rp {{ number_format($totalBelum, 0, ',', '.') }}
            </p>
            <p class="text-red-400 text-xs mt-1">Segera hubungi petugas untuk pembayaran</p>
        </div>
        @else
        <div class="bg-green-50 border border-green-300 rounded-lg p-4 mb-6 text-center">
            <p class="text-green-600 font-semibold">✅ Tidak ada denda yang belum dibayar</p>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Barang</th>
                        <th class="p-3 text-left">Jenis</th>
                        <th class="p-3 text-left">Hari Telat</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendas as $denda)
                    <tr class="border-t">
                        <td class="p-3">
                            @foreach($denda->peminjaman->details as $d)
                                {{ $d->barang->nama ?? '-' }}
                            @endforeach
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $denda->jenis == 'telat'  ? 'bg-yellow-100 text-yellow-600' :
                                   ($denda->jenis == 'hilang' ? 'bg-red-100 text-red-600' :
                                    'bg-orange-100 text-orange-600') }}">
                                {{ ucfirst($denda->jenis) }}
                            </span>
                        </td>
                        <td class="p-3">
                            {{ $denda->jumlah_hari > 0 ? $denda->jumlah_hari . ' hari' : '-' }}
                        </td>
                        <td class="p-3 font-semibold text-red-600">
                            Rp {{ number_format($denda->total, 0, ',', '.') }}
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $denda->status_bayar == 'lunas' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ ucfirst($denda->status_bayar) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-3 text-center text-gray-400">Belum ada denda</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $dendas->links() }}</div>
        </div>
    </div>
</x-app-layout>