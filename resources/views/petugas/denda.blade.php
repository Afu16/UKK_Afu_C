<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Kelola Denda</h2>
        <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600">
                    Rp {{ number_format(\App\Models\Denda::where('status_bayar','belum')->sum('total'), 0, ',', '.') }}
                </div>
                <div class="text-xs text-red-500 mt-1">Total Belum Lunas</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format(\App\Models\Denda::where('status_bayar','lunas')->sum('total'), 0, ',', '.') }}
                </div>
                <div class="text-xs text-green-500 mt-1">Total Sudah Lunas</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Siswa</th>
                        <th class="p-3 text-left">Barang</th>
                        <th class="p-3 text-left">Jenis Denda</th>
                        <th class="p-3 text-left">Hari Telat</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendas as $denda)
                    <tr class="border-t">
                        <td class="p-3">{{ $denda->peminjaman->siswa->name ?? '-' }}</td>
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
                        <td class="p-3">
                            @if($denda->status_bayar == 'belum')
                            <form method="POST" action="{{ route('petugas.denda.lunas', $denda->id) }}"
                                  onsubmit="return confirm('Tandai lunas?')">
                                @csrf
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                    Tandai Lunas
                                </button>
                            </form>
                            @else
                                <span class="text-gray-400 text-xs">✓ Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-3 text-center text-gray-400">Belum ada denda</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $dendas->links() }}</div>
        </div>
    </div>
</x-app-layout>