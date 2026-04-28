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
        // LJK
        'ljk_rill',
        'ljk_potensial',
        'ljk_recovery',
        // Konsumen
        'konsumen_rill',
        'konsumen_potensial',
        'konsumen_recovery',
        // Pihak lain
        'pihak_lain_rill',
        'pihak_lain_potensial',
        'pihak_lain_recovery',
    ];

    protected $casts = [
        'ljk_rill' => 'decimal:2',
        'ljk_potensial' => 'decimal:2',
        'ljk_recovery' => 'decimal:2',
        'konsumen_rill' => 'decimal:2',
        'konsumen_potensial' => 'decimal:2',
        'konsumen_recovery' => 'decimal:2',
        'pihak_lain_rill' => 'decimal:2',
        'pihak_lain_potensial' => 'decimal:2',
        'pihak_lain_recovery' => 'decimal:2',
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }
}
