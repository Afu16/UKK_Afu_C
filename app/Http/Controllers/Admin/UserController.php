<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rayon;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('rayon', 'rombel')
            ->where('role', '!=', 'admin')
            ->latest()
            ->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $rayons  = Rayon::all();
        $rombels = Rombel::with('rayon')->get();
        return view('admin.user.create', compact('rayons', 'rombels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas,siswa',
            'no_id'    => 'nullable|unique:users',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'no_id'     => $request->no_id,
            'rayon_id'  => $request->rayon_id,
            'rombel_id' => $request->rombel_id,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $rayons  = Rayon::all();
        $rombels = Rombel::with('rayon')->get();
        return view('admin.user.edit', compact('user', 'rayons', 'rombels'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,petugas,siswa',
            'no_id' => 'nullable|unique:users,no_id,' . $user->id,
        ]);

        $data = $request->only('name', 'email', 'role', 'no_id', 'rayon_id', 'rombel_id');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
    }
}