<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Activity Logs</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:underline text-sm mt-4 block">← Dashboard</a>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Waktu</th>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3 text-left">Aksi</th>
                        <th class="p-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-t">
                        <td class="p-3 text-xs text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="p-3">{{ $log->user->name ?? 'System' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ in_array($log->aksi, ['PINJAM','APPROVE','REQUEST']) ? 'bg-blue-100 text-blue-600' :
                                   ($log->aksi == 'KEMBALI' ? 'bg-green-100 text-green-600' :
                                   (in_array($log->aksi, ['HILANG','RUSAK']) ? 'bg-red-100 text-red-600' :
                                    'bg-gray-100 text-gray-600')) }}">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-600">{{ $log->keterangan }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-3 text-center text-gray-400">Belum ada log</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>