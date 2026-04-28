<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Barang</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.barang.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kode Barang</label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                           class="w-full border rounded px-3 py-2 @error('kode_barang') border-red-500 @enderror">
                    @error('kode_barang')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Barang</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="w-full border rounded px-3 py-2 @error('nama') border-red-500 @enderror">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded px-3 py-2">
                        <option value="buku mapel" {{ old('kategori') == 'buku mapel' ? 'selected' : '' }}>Buku Mapel</option>
                        <option value="komik" {{ old('kategori') == 'komik' ? 'selected' : '' }}>Buku Komik</option>
                        <option value="novel" {{ old('kategori') == 'novel' ? 'selected' : '' }}>Buku Novel</option>
                        <option value="kamus" {{ old('kategori') == 'kamus' ? 'selected' : '' }}>Kamus</option>
                    </select>
                    @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Stok</label>
                    <input type="number" name="stok_total" value="{{ old('stok_total', 1) }}" min="1"
                           class="w-full border rounded px-3 py-2 @error('stok_total') border-red-500 @enderror">
                    @error('stok_total')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Cover / Foto</label>
                    <input type="file" name="cover" accept="image/*"
                        class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB</p>
                    @error('cover')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">Simpan</button>
                    <a href="{{ route('admin.barang.index') }}" class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>