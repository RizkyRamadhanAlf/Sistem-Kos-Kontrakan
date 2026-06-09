<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;

class KostController extends Controller
{
    public function index()
    {
        $kosts = Kost::all();

        return view('kost.index', compact('kosts'));
    }

    public function create()
    {
        return view('kost.create');
    }

    public function store(Request $request)
    {
        Kost::create([
            'nama_kost' => $request->nama_kost,
            'alamat' => $request->alamat,
            'harga_mulai' => $request->harga_mulai,
        ]);

        return redirect()->route('kost.index');
    }

        public function edit(Kost $kost)
    {
        return view('kost.edit', compact('kost'));
    }

    public function update(Request $request, Kost $kost)
    {
        $kost->update([
            'nama_kost' => $request->nama_kost,
            'alamat' => $request->alamat,
            'harga_mulai' => $request->harga_mulai,
        ]);

        return redirect()->route('kost.index');
    }

    public function destroy(Kost $kost)
    {
        $kost->delete();

        return redirect()->route('kost.index');
    }
}
