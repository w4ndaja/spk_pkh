<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masyarakat;
use App\Models\Kriteria;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
   		#data
    	$masyarakat = DB::table('tb_datamasyarakat')->count();
    	$kriteria = DB::table('tb_kriteria')->count();
        $judul = "Data Layanan Penerima PKH desa Ndiwar";
        return view('dashboard',['judul'=>$judul, 'masyarakat'=>$masyarakat, 'kriteria'=>$kriteria]);
    }
}
