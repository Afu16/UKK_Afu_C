<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Laporan Peminjaman</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $summary['dipinjam'] }}</div>
                <div class="text-xs text-blue-500 mt-1">Dipinjam</div>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $summary['menunggu'] }}</div>
                <div class="text-xs text-yellow-500 mt-1">Menunggu</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $summary['kembali'] }}</div>
                <div class="text-xs text-green-500 mt-1">Kembali</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $summary['hilang'] }}</div>
                <div class="text-xs text-red-500 mt-1">Hilang</div>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $summary['rusak'] }}</div>
                <div class="text-xs text-orange-500 mt-1">Rusak</div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <select name="status" class="border rounded px-2 py-1.5 text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status') == 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipinjam"  {{ request('status') == 'dipinjam'  ? 'selected' : '' }}>Dipinjam</option>
                    <option value="kembali"   {{ request('status') == 'kembali'   ? 'selected' : '' }}>Kembali</option>
                    <option value="hilang"    {{ request('status') == 'hilang'    ? 'selected' : '' }}>Hilang</option>
                    <option value="rusak"     {{ request('status') == 'rusak'     ? 'selected' : '' }}>Rusak</option>
                </select>
                <input type="date" name="dari" value="{{ request('dari') }}"
                       class="border rounded px-2 py-1.5 text-sm" placeholder="Dari tanggal">
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                       class="border rounded px-2 py-1.5 text-sm" placeholder="Sampai tanggal">
                <select name="siswa_id" class="border rounded px-2 py-1.5 text-sm">
                    <option value="">Semua Siswa</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                <select name="rayon_id" class="border rounded px-2 py-1.5 text-sm">
                    <option value="">Semua Rayon</option>
                    @foreach($rayons as $r)
                        <option value="{{ $r->id }}" {{ request('rayon_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_rayon }}
                        </option>
                    @endforeach
                </select>
                <select name="rombel_id" class="border rounded px-2 py-1.5 text-sm">
                    <option value="">Semua Rombel</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_rombel }}
                        </option>
                    @endforeach
                </select>
                <div class="flex gap-2 col-span-2 md:col-span-3 lg:col-span-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-1.5 rounded text-sm">Filter</button>
                    <a href="{{ route('admin.laporan') }}" class="bg-gray-200 px-4 py-1.5 rounded text-sm">Reset</a>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Siswa</th>
                        <th class="p-3 text-left">Rayon/Rombel</th>
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
                        <td class="p-3">{{ $p->siswa->name ?? '-' }}</td>
                        <td class="p-3 text-xs">
                            {{ $p->siswa->rayon->nama_rayon ?? '-' }} /
                            {{ $p->siswa->rombel->nama_rombel ?? '-' }}
                        </td>
                        <td class="p-3">
                            @foreach($p->details as $d)
                                {{ $d->barang->nama ?? '-' }}
                            @endforeach
                        </td>
                        <td class="p-3">{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                        <td class="p-3">
                            {{ $p->tgl_kembali_rencana->format('d/m/Y') }}
                            @if($p->status === 'dipinjam' && now()->gt($p->tgl_kembali_rencana))
                                <span class="bg-red-500 text-white text-xs px-1 rounded">TERLAMBAT</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $p->status == 'dipinjam' ? 'bg-blue-100 text-blue-600' :
                                   ($p->status == 'kembali' ? 'bg-green-100 text-green-600' :
                                   ($p->status == 'hilang'  ? 'bg-red-100 text-red-600' :
                                   ($p->status == 'rusak'   ? 'bg-orange-100 text-orange-600' :
                                    'bg-yellow-100 text-yellow-600'))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($p->denda)
                                <span class="text-red-500 font-semibold text-xs">
                                    Rp {{ number_format($p->denda->total, 0, ',', '.') }}
                                    ({{ $p->denda->status_bayar }})
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-3 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $peminjamans->links() }}</div>
        </div>
    </div>
</x-app-layout>