<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValuePembobotanSub extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function pembobotan()
    {
        return $this->belongsTo(PembobotanSub::class, 'pembobotan_sub_id');
    }
}
