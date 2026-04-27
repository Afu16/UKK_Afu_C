<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Rayon;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $rombels = Rombel::with('rayon')->latest()->paginate(10);
        return view('admin.rombel.index', compact('rombels'));
    }

    public function create()
    {
        $rayons = Rayon::all();
        return view('admin.rombel.create', compact('rayons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rombel' => 'required',
        ]);
        Rombel::create($request->only('nama_rombel'));
        return redirect()->route('admin.rombel.index')->with('success', 'Rombel berhasil ditambahkan!');
    }

    public function edit(Rombel $rombel)
    {
        $rayons = Rayon::all();
        return view('admin.rombel.edit', compact('rombel', 'rayons'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $request->validate([
            'nama_rombel' => 'required',
        ]);
        $rombel->update($request->only('nama_rombel'));
        return redirect()->route('admin.rombel.index')->with('success', 'Rombel berhasil diupdate!');
    }

    public function destroy(Rombel $rombel)
    {
        $rombel->delete();
        return redirect()->route('admin.rombel.index')->with('success', 'Rombel berhasil dihapus!');
    }
}