<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Alat; // ✅ WAJIB
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori')->get();

        return view('admin.alat.alat', compact('alats'));
    }

    public function create()
{
    $kategoris = Kategori::all();
    return view('admin.alat.create', compact('kategoris'));
}
public function store(Request $request)
{
    $request->validate([
        'nama_alat'   => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'kondisi'     => 'required|in:Baik,Rusak',
    ]);

    Alat::create([
        'nama_alat'   => $request->nama_alat,
        'kategori_id' => $request->kategori_id,
        'kondisi'     => $request->kondisi,
    ]);

    return redirect()->route('alat.index')
        ->with('success', 'Alat berhasil ditambahkan');
}
public function destroy($id)
{
    $alat = Alat::findOrFail($id);
    $alat->delete();

    return redirect()->route('alat.index')
        ->with('success', 'Alat berhasil dihapus');
}
public function edit($id)
{
    $alat = Alat::findOrFail($id);
    $kategoris = Kategori::all();

    return view('admin.alat.edit', compact('alat', 'kategoris'));
}public function update(Request $request, $id)
{
    $request->validate([
        'nama_alat'   => 'required|string|max:255',
        'kategori_id' => 'required|exists:kategoris,id',
        'kondisi'     => 'required|in:Baik,Rusak',
    ]);

    $alat = Alat::findOrFail($id);
    $alat->update($request->all());

    return redirect()->route('alat.index')
        ->with('success', 'Alat berhasil diperbarui');
}
}
