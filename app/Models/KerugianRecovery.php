<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerugianRecovery extends Model
{
    use HasFactory;

    protected $table = 'kerugian_recoveries';

    protected $fillable = [
        'kerugian_fraud_id',
        'kategori', // ljk, konsumen, pihak_lain
        'amount',
        'keterangan',
        'user_id',
    ];

    public function kerugianFraud()
    {
        return $this->belongsTo(KerugianFraud::class);
    }
}
