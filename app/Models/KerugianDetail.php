<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerugianDetail extends Model
{
    use HasFactory;

    protected $table = 'kerugian_details';

    protected $fillable = [
        'kerugian_fraud_id',
        'kategori',
        'tipe',
        'nominal',
        'no_rekening',
        'keterangan',
        'user_id',
    ];

    public function kerugianFraud()
    {
        return $this->belongsTo(KerugianFraud::class);
    }
}
