<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Katalog Barang</h2>
        <a href="{{ route('siswa.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>

    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($barangs as $barang)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl text-center mb-2">
                    {{ $barang->kategori == 'buku' ? '📚' : '📚' }}
                </div>
                <h4 class="font-semibold text-center text-sm mb-1">{{ $barang->nama }}</h4>
                <p class="text-xs text-center text-gray-400 mb-1">{{ $barang->kode_barang }}</p>
                <p class="text-xs text-center mb-3">
                    Stok tersedia:
                    <span class="font-bold text-green-600">{{ $barang->stok_tersedia }}</span>
                </p>

                {{-- Form request --}}
                <form method="POST" action="{{ route('siswa.request') }}">
                    @csrf
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                    <div class="mb-2">
                        <input type="number" name="jumlah" value="1" min="1"
                               max="{{ $barang->stok_tersedia }}"
                               class="w-full border rounded px-2 py-1 text-sm text-center">
                    </div>
                    <div class="mb-2">
                        <input type="date" name="tgl_kembali_rencana"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full border rounded px-2 py-1 text-sm">
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-1.5 rounded text-sm">
                        Pinjam
                    </button>
                </form>
            </div>
            @empty
            <div class="col-span-4 text-center text-gray-400 py-12">
                Tidak ada barang tersedia saat ini.
            </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $barangs->links() }}</div>
    </div>
</x-app-layout>