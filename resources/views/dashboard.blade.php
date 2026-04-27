<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard {{ ucfirst($role) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Info user --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <p class="text-lg">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
                {{-- <p class="text-gray-500 text-sm mt-1">Role: <span class="font-semibold capitalize">{{ $role }}</span></p> --}}
            </div>

            {{-- Menu per role --}}
            @if($role === 'admin')
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <a href="{{ route('admin.barang.index')}}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center shadow">
                    📦 Kelola Buku
                </a>
                <a href="{{ route('admin.user.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center shadow">
                    👥 Kelola User
                </a>
                <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center shadow">
                    📊 Laporan
                </a>
                <a href="{{ route('admin.rayon.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg text-center shadow">
                    🏫 Rayon
                </a>
                <a href="{{ route('admin.rombel.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg text-center shadow">
                    🏫 Rombel
                </a>
                <a href="#" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center shadow">
                    📋 Activity Logs
                </a>
            </div>
            @endif

            @if($role === 'petugas')
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center shadow">
                    📋 Transaksi Pinjam
                </a>
                <a href="#" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center shadow">
                    🔄 Pengembalian
                </a>
                <a href="#" class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-lg text-center shadow">
                    💰 Denda
                </a>
            </div>
            @endif

            @if($role === 'siswa')
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center shadow">
                    📚 Katalog Barang
                </a>
                <a href="#" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center shadow">
                    📋 Riwayat Pinjam
                </a>
                <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg text-center shadow">
                    💰 Denda Saya
                </a>
            </div>
            @endif

            {{-- Tombol Logout --}}
            <div class="bg-white p-4 rounded-lg shadow">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg">
                        🚪 Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>