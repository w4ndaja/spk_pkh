<?php

namespace App\Http\Controllers;

use App\Imports\MasyarakatImport;
use App\Models\Kriteria;
use Illuminate\Http\Request;

use App\Models\Masyarakat;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class MasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $judul = "Data Masyarakat";
        $data = Masyarakat::all();
        $hamil = Kriteria::with('subKriteria')->where('kode_kri', '1')->first();
        $pendidikan = Kriteria::with('subKriteria')->where('kode_kri', '2')->first();
        $tanggungan = Kriteria::with('subKriteria')->where('kode_kri', '3')->first();
        $penghasilan = Kriteria::with('subKriteria')->where('kode_kri', '4')->first();
        $dissabilitas = Kriteria::with('subKriteria')->where('kode_kri', '5')->first();
        $umur = Kriteria::with('subKriteria')->where('kode_kri', '6')->first();
        //

        return view('masyarakat.index', [
            'data' => $data,
            'judul' => $judul,
            'hamil' => $hamil,
            'pendidikan' => $pendidikan,
            'tanggungan' => $tanggungan,
            'penghasilan' => $penghasilan,
            'dissabilitas' => $dissabilitas,
            'umur' => $umur,
        ]);
    }

    public function download()
    {
        $judul = "Data Masyarakat";
        $data = Masyarakat::all();
        return view('masyarakat.download', ['data' => $data, 'judul' => $judul]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Masyarakat::create($request->all());

        return redirect('list-masyarakat')->with('input', 'data berhasil ditambah');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $masyarakat = DB::table('tb_datamasyarakat')->where('id_ms', $id)->first();
        return view('masyarakat.edit', ['data' => $masyarakat]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Masyarakat::where('id_ms', $id)
            ->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'alamat' => $request->alamat
            ]);
        return redirect('list-masyarakat')->with('pesan', "Data Berhasil Diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tb_datamasyarakat')->where('id_ms', '=', $id)->delete();

        return redirect('masyarakat')->with('pesan', "Data Berhasil Dihapus");
    }

    public function upload(Request $req)
    {
        Excel::import(new MasyarakatImport, $req->file('excel'), null, \Maatwebsite\Excel\Excel::XLSX);
        return back()->with('input', 'Data berhasil di impor!');
    }
}
