<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edit Rayon</h2></x-slot>
    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.rayon.update', $rayon) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Rayon</label>
                    <input type="text" name="nama_rayon" 
                           value="{{ old('nama_rayon', $rayon->nama_rayon) }}"
                           class="w-full border rounded px-3 py-2">
                    @error('nama_rayon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded">
                        Update
                    </button>
                    <a href="{{ route('admin.rayon.index') }}" class="bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>