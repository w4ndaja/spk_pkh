<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masyarakat extends Model
{
	public $timestamps = false;
    protected $guarded = [];
    protected $table = 'tb_datamasyarakat';
    public $primaryKey = 'id_ms';
}
