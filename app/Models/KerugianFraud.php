<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerugianFraud extends Model
{
    use HasFactory;

    protected $table = 'kerugian_fraud';

    protected $fillable = [
        'kasus_id',
        'ljk_rill',
        'ljk_potensial',
        'ljk_recovery',
        'konsumen_rill',
        'konsumen_potensial',
        'konsumen_recovery',
        'pihak_lain_rill',
        'pihak_lain_potensial',
        'pihak_lain_recovery',
    ];

    // Hapus atau komentari bagian ini jika masalah 0 tetap muncul
    // Karena casting ke decimal:0 seringkali mengubah null menjadi "0"
    protected $casts = [
        // 'ljk_rill' => 'decimal:0', 
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }
}