<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rayon;
use App\Models\Rombel;
use App\Models\Barang;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Rayon
        $rayon1 = Rayon::create(['nama_rayon' => 'Tarkal']);
        $rayon2 = Rayon::create(['nama_rayon' => 'Leles']);

        // Rombel
        $rombel1 = Rombel::create(['nama_rombel' => 'XII PPLG']);
        $rombel2 = Rombel::create(['nama_rombel' => 'XII TJKT']);

        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@perpus.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Petugas
        User::create([
            'name'     => 'Petugas Satu',
            'email'    => 'petugas@perpus.com',
            'password' => Hash::make('password'),
            'role'     => 'petugas',
        ]);

        // Siswa
        User::create([
            'name'      => 'Siswa Satu',
            'email'     => 'siswa1@perpus.com',
            'password'  => Hash::make('password'),
            'role'      => 'siswa',
            'no_id'     => 'S001',
            'rayon_id'  => $rayon1->id,
            'rombel_id' => $rombel1->id,
        ]);

        User::create([
            'name'      => 'Siswa Dua',
            'email'     => 'siswa2@perpus.com',
            'password'  => Hash::make('password'),
            'role'      => 'siswa',
            'no_id'     => 'S002',
            'rayon_id'  => $rayon2->id,
            'rombel_id' => $rombel2->id,
        ]);

        // Barang sample
        Barang::create([
            'kode_barang'    => 'BK001',
            'nama'           => 'Buku Matematika',
            'kategori'       => 'buku mapel',
            'stok_total'     => 5,
            'stok_tersedia'  => 5,
        ]);

        Barang::create([
            'kode_barang'    => 'BK002',
            'nama'           => 'Tere liye Pulang',
            'kategori'       => 'novel',
            'stok_total'     => 3,
            'stok_tersedia'  => 3,
        ]);

            Barang::create([
            'kode_barang'    => 'BKI02',
            'nama'           => 'KBI',
            'kategori'       => 'kamus',
            'stok_total'     => 10,
            'stok_tersedia'  => 10,
        ]);

    }
}