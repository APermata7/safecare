<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;

class AdminPantiController extends Controller
{
    public function index()
    {
        $pantis = PantiAsuhan::all();
        return view('admin.pantis.index', compact('pantis'));
    }

    public function create()
    {
        return view('admin.pantis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panti' => 'required|string|max:255|unique:panti_asuuhan,nama_panti',
            'alamat' => 'required|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('panti-images', 'public');
        }

        PantiAsuhan::create($validated);

        return redirect()->route('admin.panti.index')
            ->with('success', 'Panti berhasil ditambahkan');
    }

    public function show($id)
    {
        $panti = PantiAsuhan::findOrFail($id);
        return view('admin.pantis.show', compact('panti'));
    }

    public function edit($id)
    {
        $panti = PantiAsuhan::findOrFail($id);
        return view('admin.pantis.edit', compact('panti'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_panti' => 'required|string|max:255',
            'alamat'     => 'required|string',
        ]);

        $panti = PantiAsuhan::findOrFail($id);
        $panti->update($request->only('nama_panti', 'alamat'));

        return redirect()->route('admin.panti.index')->with('success', 'Data panti berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panti = PantiAsuhan::findOrFail($id);
        $panti->delete();

        return redirect()->route('admin.panti.index')->with('success', 'Panti berhasil dihapus.');
    }
}
