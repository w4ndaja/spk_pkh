<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembobotanAlternatif extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function updatePembobotan($form)
    {
        foreach ($form as $idMasyarakat => $item) {
            foreach ($item as $idSubKri => $_item) {
                $l = $_item['l'];
                $m = $_item['m'];
                $u = $_item['u'];
                PembobotanAlternatif::updateOrCreate([
                    'masyarakat_id' => $idMasyarakat,
                    'sub_kriteria_id' => $idSubKri,
                ], [
                    'l' => $l,
                    'm' => $m,
                    'u' => $u,
                ]);
            }
        }
    }
}
