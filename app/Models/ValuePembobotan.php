<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValuePembobotan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];    
    public function pembobotan()
    {
        return $this->belongsTo(Pembobotan::class, 'pembobotan_id');
    }
}
