<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencegahanFraud extends Model
{
    use HasFactory;

    protected $table = 'pencegahan_fraud';

    protected $fillable = [
        'kasus_id',
        'pencegahan_id',
        'keterangan',
        'target_waktu',
        'realisasi',
    ];

    protected $casts = [
        'target_waktu' => 'date',
        'realisasi' => 'date',
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }

    public function refPencegahan()
    {
        return $this->belongsTo(RefPencegahanFraud::class, 'pencegahan_id');
    }

    public function pencegahan()
    {
        return $this->belongsTo(RefPencegahanFraud::class, 'pencegahan_id');
    }
}
