<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
 public function index()
{
    $kategoris = Kategori::all();
    return view('admin.kategori-alat.kategori-alat', compact('kategoris'));
}

    public function store(Request $request)
    {
        
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }
    public function destroy($id)
{
    $kategori = Kategori::findOrFail($id);
    $kategori->delete();

    return redirect()->back()->with('success', 'Kategori berhasil dihapus');
}
    public function edit($id)
{
    $kategori = Kategori::findOrFail($id);
    return view('admin.kategori-alat.edit', compact('kategori'));
}
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:255'
    ]);

    $kategori = Kategori::findOrFail($id);
    $kategori->update([
        'nama_kategori' => $request->nama_kategori
    ]);

    return redirect()->route('admin.kategori.index')
        ->with('success', 'Kategori berhasil diperbarui');
}

}
