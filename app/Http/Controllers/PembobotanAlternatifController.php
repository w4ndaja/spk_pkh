<?php

namespace App\Http\Controllers;

use App\Models\PembobotanAlternatif;
use Illuminate\Http\Request;

class PembobotanAlternatifController extends Controller
{
    public function update(Request $req)
    {
        $form = $req->pembobotan_alternatif;
        PembobotanAlternatif::updatePembobotan($form);
        return redirect()->route('hasil-akhir.index');
    }
}
