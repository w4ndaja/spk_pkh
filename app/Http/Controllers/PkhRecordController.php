<?php

namespace App\Http\Controllers;

use App\Exports\PkhRecordExport;
use App\Imports\PkhrecordImport;
use App\Models\Masyarakat;
use App\Models\PkhRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PkhRecordController extends Controller
{
    public function index()
    {
        $data = PkhRecord::all();
        $masyarakat = Masyarakat::all();
        return view('pkh-record.index', [
            'data' => $data,
            'judul' => 'Data PKH',
            'masyarakat' => $masyarakat
        ]);
    }

    public function store(Request $request)
    {
        $form = $request->all();
        PkhRecord::create($form);
        return back()->with('input', 'Data berhasil ditambah!');
    }

    public function update(Request $request, PkhRecord $pkhRecord)
    {
        $form = $request->all();
        $pkhRecord->update($form);
        return back()->with('input', 'Data berhasil diperbaharui!');
    }

    public function destroy(PkhRecord $pkhRecord)
    {
        $pkhRecord->delete();
        return back()->with('input', 'Data berhasil dihapus!');
    }

    public function export(Request $request)
    {
        return Excel::download(new PkhRecordExport, 'pkh.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new PkhrecordImport, $request->file('excel'), null, \Maatwebsite\Excel\Excel::XLSX);
        return back()->with('input', 'Data berhasil di impor!');
    }
}
