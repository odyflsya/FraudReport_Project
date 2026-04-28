<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuFraud extends Model
{
    use HasFactory;

    protected $table = 'waktu_fraud';

    protected $fillable = [
        'kasus_id',
        'waktu_awal',
        'waktu_akhir',
        'waktu_diketahui',
    ];

    protected $casts = [
        'waktu_awal' => 'datetime',
        'waktu_akhir' => 'datetime',
        'waktu_diketahui' => 'datetime',
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }
}
