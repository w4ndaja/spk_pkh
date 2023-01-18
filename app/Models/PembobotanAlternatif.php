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
                $l = array_key_exists('l', $_item) ? $_item['l'] : 0.2;
                $m = array_key_exists('m', $_item) ? $_item['m'] : 0.2;
                $u = array_key_exists('u', $_item) ? $_item['u'] : 0.2;
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
