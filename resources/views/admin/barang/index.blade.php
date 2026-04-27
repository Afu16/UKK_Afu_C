<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Kelola Buku</h2>
                <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:underline text-sm">← Kembali ke Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Daftar Buku</h3>
                <a href="{{ route('admin.barang.create') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Buku
                </a>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Nama Buku</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Stok Total</th>
                        <th class="p-3 text-left">Stok Tersedia</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="border-t">
                        <td class="p-3">{{ $barang->kode_barang }}</td>
                        <td class="p-3">{{ $barang->nama }}</td>
                        <td class="p-3 capitalize">{{ $barang->kategori }}</td>
                        <td class="p-3">{{ $barang->stok_total }}</td>
                        <td class="p-3">
                            <span class="{{ $barang->stok_tersedia == 0 ? 'text-red-500 font-bold' : 'text-green-600' }}">
                                {{ $barang->stok_tersedia }}
                            </span>
                        </td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.barang.edit', $barang) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}"
                                  onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-3 text-center text-gray-400">Belum ada barang</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $barangs->links() }}</div>
        </div>

    </div>
</x-app-layout>