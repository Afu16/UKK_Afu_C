<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Tambah Peminjaman</h2></x-slot>

    <div class="py-8 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('petugas.peminjaman.store') }}" id="form-peminjaman">
                @csrf

                {{-- SISWA --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Siswa</label>

                    {{-- Scan barcode siswa --}}
                    <div class="flex gap-2 mb-2">
                        <input type="text" id="scan-siswa" placeholder="Scan / ketik No ID siswa..."
                               class="w-full border rounded px-3 py-2 text-sm"
                               oninput="cariSiswa(this.value)">
                        <button type="button" onclick="toggleScanner('scanner-siswa', 'scan-siswa', 'cariSiswa')"
                                class="bg-gray-600 text-white px-3 py-2 rounded text-sm whitespace-nowrap">
                            📷 Scan
                        </button>
                    </div>
                    <div id="scanner-siswa" style="display:none" class="mb-2"></div>

                    {{-- Dropdown siswa --}}
                    <select name="siswa_id" id="siswa_id" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">-- Pilih Siswa Manual --</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}"
                                    data-noid="{{ $siswa->no_id }}"
                                    {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->name }} ({{ $siswa->no_id ?? 'no id' }})
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- BARANG --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Barang</label>

                    {{-- Scan barcode barang --}}
                    <div class="flex gap-2 mb-2">
                        <input type="text" id="scan-barang" placeholder="Scan / ketik kode barang..."
                               class="w-full border rounded px-3 py-2 text-sm"
                               oninput="cariBarang(this.value)">
                        <button type="button" onclick="toggleScanner('scanner-barang', 'scan-barang', 'cariBarang')"
                                class="bg-gray-600 text-white px-3 py-2 rounded text-sm whitespace-nowrap">
                            📷 Scan
                        </button>
                    </div>
                    <div id="scanner-barang" style="display:none" class="mb-2"></div>

                    {{-- Dropdown barang --}}
                    <select name="barang_id" id="barang_id" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">-- Pilih Barang Manual --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}"
                                    data-kode="{{ $barang->kode_barang }}"
                                    {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
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

    {{-- html5-qrcode dari CDN --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let scanners = {};

        function toggleScanner(scannerId, inputId, callbackName) {
            const container = document.getElementById(scannerId);
            if (container.style.display === 'none') {
                container.style.display = 'block';
                startScanner(scannerId, inputId, callbackName);
            } else {
                stopScanner(scannerId);
                container.style.display = 'none';
            }
        }

        function startScanner(scannerId, inputId, callbackName) {
            const scanner = new Html5Qrcode(scannerId);
            scanners[scannerId] = scanner;

            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 100 } },
                (decodedText) => {
                    document.getElementById(inputId).value = decodedText;
                    window[callbackName](decodedText);
                    stopScanner(scannerId);
                    document.getElementById(scannerId).style.display = 'none';
                },
                () => {}
            ).catch(err => {
                alert('Tidak bisa akses kamera: ' + err);
            });
        }

        function stopScanner(scannerId) {
            if (scanners[scannerId]) {
                scanners[scannerId].stop().catch(() => {});
                scanners[scannerId] = null;
            }
        }

        // Cari siswa berdasarkan no_id
        function cariSiswa(val) {
            const select = document.getElementById('siswa_id');
            val = val.trim().toLowerCase();
            for (let opt of select.options) {
                if (opt.dataset.noid && opt.dataset.noid.toLowerCase() === val) {
                    select.value = opt.value;
                    return;
                }
            }
        }

        // Cari barang berdasarkan kode
        function cariBarang(val) {
            const select = document.getElementById('barang_id');
            val = val.trim().toLowerCase();
            for (let opt of select.options) {
                if (opt.dataset.kode && opt.dataset.kode.toLowerCase() === val) {
                    select.value = opt.value;
                    return;
                }
            }
        }
    </script>
</x-app-layout>