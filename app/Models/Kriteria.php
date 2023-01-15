<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    public $timestamps = false;
    protected $fillable = ['kode_kri', 'nama_kri', 'dari', 'sampai'];

    protected $table = 'tb_kriteria';
    protected $primaryKey = 'id_kri';

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kri', 'id_kri');
    }

    public function pembobotans()
    {
        return $this->hasMany(Pembobotan::class, 'kriteria_id');
    }
}
