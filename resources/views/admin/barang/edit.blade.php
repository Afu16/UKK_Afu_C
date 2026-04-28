<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Barang</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.barang.update', $barang) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kode Barang</label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}"
                           class="w-full border rounded px-3 py-2">
                    @error('kode_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Barang</label>
                    <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}"
                           class="w-full border rounded px-3 py-2">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded px-3 py-2">
                        <option value="buku mapel" {{ $barang->kategori == 'buku mapel' ? 'selected' : '' }}>Buku Mapel</option>
                        <option value="komik" {{ $barang->kategori == 'komik' ? 'selected' : '' }}>Buku Komik</option>
                        <option value="novel" {{ $barang->kategori == 'novel' ? 'selected' : '' }}>Buku Novel</option>
                        <option value="kamus" {{ $barang->kategori == 'kamus' ? 'selected' : '' }}>Kamus</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Stok Total</label>
                    <input type="number" name="stok_total" value="{{ old('stok_total', $barang->stok_total) }}" min="1"
                           class="w-full border rounded px-3 py-2">
                    @error('stok_total')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Cover / Foto</label>
                    @if($barang->cover)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $barang->cover) }}"
                            class="h-24 w-auto rounded border object-cover" alt="Cover">
                        <p class="text-xs text-gray-400 mt-1">Cover saat ini</p>
                    </div>
                    @endif

                    <input type="file" name="cover" accept="image/*"
                        class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah cover</p>
                    @error('cover')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded">Update</button>
                    <a href="{{ route('admin.barang.index') }}" class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>