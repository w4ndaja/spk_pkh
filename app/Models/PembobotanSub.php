<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembobotanSub extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pembobotanSubValues()
    {
        return $this->hasMany(ValuePembobotanSub::class, 'pembobotan_sub_id');
    }
}
