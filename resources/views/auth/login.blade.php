<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Perpustakaan</h2>
        <p class="text-gray-500 text-sm mt-1">Masuk untuk meminjam buku</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" 
                required autofocus autocomplete="username" 
                placeholder="user@perpustakaan.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="Masukkan password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" 
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                    name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button class="ms-auto bg-indigo-600 hover:bg-indigo-700">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 text-center text-xs text-gray-400 border-t border-gray-200">
            <p>⚠️ Akun hanya dibuat oleh admin perpustakaan</p>
            <div class="flex justify-center gap-3 mt-2">
                <span class="px-2 py-0.5 bg-gray-100 rounded">Petugas</span>
                <span class="px-2 py-0.5 bg-gray-100 rounded">Peminjam/Siswa</span>
            </div>
        </div>
    </form>
</x-guest-layout>