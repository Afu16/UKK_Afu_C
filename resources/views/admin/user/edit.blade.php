<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edit User</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.user.update', $user) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password <span class="text-gray-400 text-xs">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role" id="role" class="w-full border rounded px-3 py-2" onchange="toggleSiswaField()">
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="siswa"   {{ $user->role == 'siswa'   ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>

                <div id="siswa-fields">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">No ID / Barcode / KTP</label>
                        <input type="text" name="no_id" value="{{ old('no_id', $user->no_id) }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Rayon</label>
                        <select name="rayon_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Rayon --</option>
                            @foreach($rayons as $rayon)
                                <option value="{{ $rayon->id }}" {{ $user->rayon_id == $rayon->id ? 'selected' : '' }}>
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
                                <option value="{{ $rombel->id }}" {{ $user->rombel_id == $rombel->id ? 'selected' : '' }}>
                                    {{ $rombel->nama_rombel }} ({{ $rombel->rayon->nama_rayon ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded">Update</button>
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
        toggleSiswaField();
    </script>
</x-app-layout>