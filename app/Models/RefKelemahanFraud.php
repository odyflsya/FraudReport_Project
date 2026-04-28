<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKelemahanFraud extends Model
{
    use HasFactory;

    protected $table = 'ref_kelemahan_fraud';

    protected $fillable = ['nama'];

    public function kasus()
    {
        return $this->belongsToMany(
            Kasus::class,
            'kasus_kelemahan',
            'kelemahan_id',
            'kasus_id'
        )->withPivot('keterangan')->withTimestamps();
    }
}