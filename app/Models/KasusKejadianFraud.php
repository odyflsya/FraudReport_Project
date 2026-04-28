<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasusKejadianFraud extends Model
{
    use HasFactory;

    protected $fillable = ['kasus_id', 'kejadian_id', 'kode_kejadian'];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }

    public function kejadianFraud()
    {
        return $this->belongsTo(RefKejadianFraud::class, 'kejadian_id');
    }
}