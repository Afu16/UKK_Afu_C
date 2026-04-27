<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rayon;
use Illuminate\Http\Request;

class RayonController extends Controller
{
    public function index()
    {
        $rayons = Rayon::latest()->paginate(10);
        return view('admin.rayon.index', compact('rayons'));
    }

    public function create()
    {
        return view('admin.rayon.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_rayon' => 'required|unique:rayons']);
        Rayon::create($request->only('nama_rayon'));
        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil ditambahkan!');
    }

    public function edit(Rayon $rayon)
    {
        return view('admin.rayon.edit', compact('rayon'));
    }

    public function update(Request $request, Rayon $rayon)
    {
        $request->validate(['nama_rayon' => 'required|unique:rayons,nama_rayon,' . $rayon->id]);
        $rayon->update($request->only('nama_rayon'));
        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil diupdate!');
    }

    public function destroy(Rayon $rayon)
    {
        $rayon->delete();
        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil dihapus!');
    }
}