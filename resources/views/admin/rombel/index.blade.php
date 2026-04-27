<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Kelola Rombel</h2>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Daftar Rombel</h3>
                <a href="{{ route('admin.rombel.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Nama Rombel</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rombels as $rombel)
                    <tr class="border-t">
                        <td class="p-3">{{ $rombel->nama_rombel }}</td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.rombel.edit', $rombel) }}" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.rombel.destroy', $rombel) }}" onsubmit="return confirm('Yakin?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="p-3 text-center text-gray-400">Belum ada rombel</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $rombels->links() }}</div>
        </div>
    </div>
</x-app-layout>