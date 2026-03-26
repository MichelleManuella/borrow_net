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
        $search = request('q');

        $alatsQuery = Alat::with('kategori');

        if ($search) {
            $alatsQuery->where('nama_alat', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($query) use ($search) {
                    $query->where('nama_kategori', 'like', '%' . $search . '%');
                });
        }

        $alats = $alatsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.alat.alat', compact('alats', 'search'));
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
            'stok'        => 'required|integer|min:0',
        ]);

        Alat::create([
            'nama_alat'   => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'kondisi'     => $request->kondisi,
            'stok'        => $request->stok,
        ]);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan');
    }
    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus');
    }
    public function edit($id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::all();

        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_alat'   => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi'     => 'required|in:Baik,Rusak',
            'stok'        => 'required|integer|min:0',
        ]);

        $alat = Alat::findOrFail($id);
        $alat->update($request->only(['nama_alat', 'kategori_id', 'kondisi', 'stok']));

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diperbarui');
    }
}
