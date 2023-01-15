<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    public function index(Request $request, $kriteria)
    {
        $kriteria = Kriteria::with('subKriteria')->findOrFail($kriteria);
        return view('sub-kriteria.index', [
            'judul' => 'Variabel Linguistik Sub Kriteria ' . $kriteria->nama_kri,
            'data' => $kriteria->subKriteria,
            'kriteria' => $kriteria,
        ]);
    }

    public function store(Request $request, $kriteria)
    {
        $kriteria = Kriteria::findOrFail($kriteria);
        $kriteria->subKriteria()->create($request->all());
        return back()->with('input', 'Variabel Linguistik Sub Kriteria berhasil ditambah!');
    }

    public function update(Request $request, $kriteria, SubKriteria $subKriterium)
    {
        Kriteria::findOrFail($kriteria);
        $subKriterium->update($request->all());
        return back()->with('input', 'Variabel Linguistik Sub Kriteria berhasil diperbaharui!');
    }

    public function destroy(Request $request, $kriteria, SubKriteria $subKriterium)
    {
        Kriteria::findOrFail($kriteria);
        $subKriterium->delete();
        return back()->with('input', 'Variabel Linguistik Sub Kriteria berhasil dihapus!');
    }
}
