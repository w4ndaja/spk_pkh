<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

use App\Models\Masyarakat;
use DB;

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
        $kriteria = Kriteria::with('subKriteria')->get();
        return view('masyarakat.index', ['data' => $data, 'judul' => $judul, 'kriteria' => $kriteria]);
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

        return redirect('masyarakat')->with('input', 'data berhasil ditambah');
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
        return redirect('Masyarakat')->with('pesan', "Data Berhasil Diubah");
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
}
