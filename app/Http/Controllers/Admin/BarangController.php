<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->paginate(10);
        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs',
            'nama'        => 'required',
            'kategori' => 'required|in:buku mapel,komik,novel,kamus',
            'stok_total'  => 'required|integer|min:1',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Barang::create([
            'kode_barang'   => $request->kode_barang,
            'nama'          => $request->nama,
            'kategori'      => $request->kategori,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'cover'         => $coverPath,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama'        => 'required',
            'kategori' => 'required|in:buku mapel,komik,novel,kamus',
            'stok_total'  => 'required|integer|min:1',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = $barang->cover;
        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($barang->cover) {
                \Storage::disk('public')->delete($barang->cover);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $barang->update([
            'kode_barang'   => $request->kode_barang,
            'nama'          => $request->nama,
            'kategori'      => $request->kategori,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'cover'         => $coverPath,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function label(Barang $barang)
    {
        return view('admin.barang.label', compact('barang'));
    }
}