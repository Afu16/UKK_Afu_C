<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Katalog Buku</h2>
                <a href="{{ route('siswa.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-6 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        {{-- STEP 1: Pilih Kategori --}}
        @if(!$kategori && !request('barang_id'))
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Pilih Kategori</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($kategoris as $kat)
                @php
                    $icon = match($kat) {
                        'buku mapel' => '📖',
                        'komik'      => '🎭',
                        'novel'      => '📕',
                        'kamus'      => '📘',
                        default      => '📚',
                    };
                @endphp
                <a href="{{ route('siswa.katalog', ['kategori' => $kat]) }}"
                   class="bg-white hover:bg-blue-50 border-2 hover:border-blue-400 rounded-xl p-6 text-center shadow transition">
                    <div class="text-5xl mb-3">{{ $icon }}</div>
                    <div class="font-semibold capitalize text-gray-700">{{ $kat }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- STEP 2: Pilih Buku dari Kategori --}}
        @if($kategori && !request('barang_id'))
        <div class="mb-4 flex items-center gap-3">
            <a href="{{ route('siswa.katalog') }}" class="text-gray-500 hover:underline text-sm">← Kategori</a>
            <span class="text-gray-400">/</span>
            <span class="font-semibold capitalize text-gray-700">{{ $kategori }}</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($barangs as $barang)
            <a href="{{ route('siswa.katalog', ['kategori' => $kategori, 'barang_id' => $barang->id]) }}"
               class="bg-white rounded-xl shadow hover:shadow-md transition p-4 text-center block">
                <div class="flex justify-center mb-3">
                    @if($barang->cover)
                        <img src="{{ asset('storage/' . $barang->cover) }}"
                             class="h-36 w-28 object-cover rounded border" alt="{{ $barang->nama }}">
                    @else
                    @php
                        $icon = match($barang->kategori) {
                            'buku mapel' => '📖',
                            'komik'      => '🎭',
                            'novel'      => '📕',
                            'kamus'      => '📘',
                            default      => '📚',
                        };
                    @endphp
                    <div class="h-36 w-28 bg-gray-100 rounded border flex items-center justify-center text-5xl">
                        {{ $icon }}
                    </div>
                    @endif
                </div>
                <div class="font-semibold text-sm text-gray-800">{{ $barang->nama }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $barang->kode_barang }}</div>
                <div class="text-xs mt-1">
                    Stok: <span class="font-bold text-green-600">{{ $barang->stok_tersedia }}</span>
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center text-gray-400 py-12">
                Tidak ada buku tersedia di kategori ini.
            </div>
            @endforelse
        </div>
        @endif

        {{-- STEP 3: Form Pinjam --}}
        @if(request('barang_id') && $barang)
        <div class="mb-4 flex items-center gap-3">
            <a href="{{ route('siswa.katalog') }}" class="text-gray-500 hover:underline text-sm">← Kategori</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('siswa.katalog', ['kategori' => $kategori]) }}"
               class="text-gray-500 hover:underline text-sm capitalize">{{ $kategori }}</a>
            <span class="text-gray-400">/</span>
            <span class="font-semibold text-gray-700">{{ $barang->nama }}</span>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-xl shadow p-6">
            {{-- Info buku --}}
            <div class="flex gap-4 mb-6">
                @if($barang->cover)
                    <img src="{{ asset('storage/' . $barang->cover) }}"
                         class="h-32 w-24 object-cover rounded border flex-shrink-0" alt="{{ $barang->nama }}">
                @else
                @php
                    $icon = match($barang->kategori) {
                        'buku mapel' => '📖',
                        'komik'      => '🎭',
                        'novel'      => '📕',
                        'kamus'      => '📘',
                        default      => '📚',
                    };
                @endphp
                <div class="h-32 w-24 bg-gray-100 rounded border flex items-center justify-center text-5xl flex-shrink-0">
                    {{ $icon }}
                </div>
                @endif
                <div>
                    <h3 class="font-bold text-lg">{{ $barang->nama }}</h3>
                    <p class="text-sm text-gray-500 capitalize">{{ $barang->kategori }}</p>
                    <p class="text-sm text-gray-400">{{ $barang->kode_barang }}</p>
                    <p class="text-sm mt-2">
                        Stok tersedia:
                        <span class="font-bold text-green-600">{{ $barang->stok_tersedia }}</span>
                    </p>
                </div>
            </div>

            {{-- Form pinjam --}}
<form method="POST" action="{{ route('siswa.request') }}">
    @csrf
    <input type="hidden" name="barang_id" value="{{ $barang->id }}">

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Jumlah</span>
            <span class="font-semibold">1 buku</span>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Tanggal Kembali</label>
        <input type="date" name="tgl_kembali_rencana"
               min="{{ \Carbon\Carbon::today()->addDay()->format('Y-m-d') }}"
               max="{{ \Carbon\Carbon::today()->addWeeks(1)->format('Y-m-d') }}"
               class="w-full border rounded px-3 py-2 text-sm @error('tgl_kembali_rencana') border-red-500 @enderror">
        <p class="text-xs text-gray-400 mt-1">
            Maksimal: {{ \Carbon\Carbon::today()->addWeeks(1)->format('d/m/Y') }}
        </p>
        @error('tgl_kembali_rencana')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded font-semibold">
        Pinjam Sekarang
    </button>
</form>
        </div>
        @endif
    </div>
</x-app-layout>