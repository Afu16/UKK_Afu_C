<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Tambah User</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.user.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role" id="role" class="w-full border rounded px-3 py-2" onchange="toggleSiswaField()">
                        <option value="">-- Pilih Role --</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="siswa"   {{ old('role') == 'siswa'   ? 'selected' : '' }}>Siswa</option>
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Field khusus siswa --}}
                <div id="siswa-fields" style="display:none">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">No ID / Barcode / KTP</label>
                        <input type="text" name="no_id" value="{{ old('no_id') }}" class="w-full border rounded px-3 py-2">
                        @error('no_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Rayon</label>
                        <select name="rayon_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Rayon --</option>
                            @foreach($rayons as $rayon)
                                <option value="{{ $rayon->id }}" {{ old('rayon_id') == $rayon->id ? 'selected' : '' }}>
                                    {{ $rayon->nama_rayon }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Rombel</label>
                        <select name="rombel_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Rombel --</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}" {{ old('rombel_id') == $rombel->id ? 'selected' : '' }}>
                                    {{ $rombel->nama_rombel }} ({{ $rombel->rayon->nama_rayon ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-2">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Simpan</button>
                    <a href="{{ route('admin.user.index') }}" class="bg-gray-200 px-6 py-2 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSiswaField() {
            const role = document.getElementById('role').value;
            document.getElementById('siswa-fields').style.display = role === 'siswa' ? 'block' : 'none';
        }
        // Jalankan saat load kalau old value siswa
        toggleSiswaField();
    </script>
</x-app-layout>