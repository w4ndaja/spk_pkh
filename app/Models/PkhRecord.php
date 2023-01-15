<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkhRecord extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'masyarakat_id', 'id_ms');
    }
}
