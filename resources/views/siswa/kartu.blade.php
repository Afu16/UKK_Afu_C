<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Kartu Saya</h2></x-slot>

    <div class="py-8 max-w-sm mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6 text-center" id="kartu">
            <div class="text-lg font-bold mb-1">{{ auth()->user()->name }}</div>
            <div class="text-sm text-gray-500 mb-1">
                {{ auth()->user()->rombel->nama_rombel ?? '-' }} — 
                {{ auth()->user()->rayon->nama_rayon ?? '-' }}
            </div>
            <div class="text-xs text-gray-400 mb-4">No ID: {{ auth()->user()->no_id ?? '-' }}</div>

            <div class="flex justify-center mb-4">
                <svg id="barcode"></svg>
            </div>

            <div class="text-xs text-gray-400">{{ auth()->user()->no_id }}</div>
        </div>

        <div class="flex gap-3 mt-4 no-print">
            <button onclick="window.print()"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
                🖨️ Print Kartu
            </button>
            <a href="{{ route('siswa.dashboard') }}"
               class="w-full bg-gray-200 hover:bg-gray-300 text-center py-2 rounded">
                Kembali
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        document.title = "Kartu - {{ auth()->user()->name }}";
        JsBarcode("#barcode", "{{ auth()->user()->no_id ?? 'NO-ID' }}", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
        });
    </script>

    <style>
        @media print {
            nav, header, .no-print { display: none !important; }
            #kartu { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</x-app-layout>