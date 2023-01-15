<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembobotan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function valuePembobotans()
    {
        return $this->hasMany(ValuePembobotan::class, 'pembobotan_id');
    }
}
