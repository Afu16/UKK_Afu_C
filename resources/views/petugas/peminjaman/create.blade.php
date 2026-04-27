<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Tambah Peminjaman</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('petugas.peminjaman.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Siswa</label>
                    <select name="siswa_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->name }} ({{ $siswa->no_id ?? 'no id' }})
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Barang</label>
                    <select name="barang_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama }} - Stok: {{ $barang->stok_tersedia }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                           class="w-full border rounded px-3 py-2">
                    @error('jumlah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Tanggal Kembali Rencana</label>
                    <input type="date" name="tgl_kembali_rencana" value="{{ old('tgl_kembali_rencana') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border rounded px-3 py-2">
                    @error('tgl_kembali_rencana')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Simpan</button>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="bg-gray-200 px-6 py-2 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>