<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Kelola User</h2></x-slot>
    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Daftar User</h3>
                <a href="{{ route('admin.user.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">+ Tambah User</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">No ID</th>
                        <th class="p-3 text-left">Rayon</th>
                        <th class="p-3 text-left">Rombel</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $user->role == 'admin' ? 'bg-red-100 text-red-600' :
                                   ($user->role == 'petugas' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $user->no_id ?? '-' }}</td>
                        <td class="p-3">{{ $user->rayon->nama_rayon ?? '-' }}</td>
                        <td class="p-3">{{ $user->rombel->nama_rombel ?? '-' }}</td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.user.edit', $user) }}" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.user.destroy', $user) }}" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-3 text-center text-gray-400">Belum ada user</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $users->links() }}</div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </div>
</x-app-layout>