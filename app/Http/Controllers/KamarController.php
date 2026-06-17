<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('kost')->get();

        return view('kamar.index', compact('kamars'));
    }

    public function create()
    {
        $kosts = Kost::all();

        return view('kamar.create', compact('kosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kost_id' => 'required',
            'nomor_kamar' => 'required',
            'harga' => 'required|numeric',
            'kapasitas' => 'required|numeric',
        ]);

        Kamar::create([
            'kost_id' => $request->kost_id,
            'nomor_kamar' => $request->nomor_kamar,
            'harga' => $request->harga,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
            'fasilitas' => $request->fasilitas,
        ]);

        return redirect()
            ->route('kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function show(Kamar $kamar)
    {
        return view('kamar.show', compact('kamar'));
    }

    public function edit(Kamar $kamar)
    {
        $kosts = Kost::all();

        return view('kamar.edit', compact('kamar', 'kosts'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'kost_id' => 'required',
            'nomor_kamar' => 'required',
            'harga' => 'required|numeric',
            'kapasitas' => 'required|numeric',
        ]);

        $kamar->update([
            'kost_id' => $request->kost_id,
            'nomor_kamar' => $request->nomor_kamar,
            'harga' => $request->harga,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status ?? 'tersedia',
            'fasilitas' => $request->fasilitas,
        ]);

        return redirect()
            ->route('kamar.index')
            ->with('success', 'Kamar berhasil diperbarui');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();

        return redirect()
            ->route('kamar.index')
            ->with('success', 'Kamar berhasil dihapus');
    }
}
