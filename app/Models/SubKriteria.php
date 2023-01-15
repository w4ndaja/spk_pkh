<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    public $timestamps = false;
    protected $guarded = ['id_sub'];
    protected $primaryKey = 'id_sub';

    protected $table = 'tb_subkri';


    public function pembobotans()
    {
        return $this->hasMany(PembobotanSub::class, 'sub_kriteria_id', 'id_sub');
    }
}
