<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefLokasiFraud extends Model
{
    use HasFactory;

    protected $table = 'ref_lokasi_fraud';

    protected $fillable = ['nama'];

    public function kasus()
    {
        return $this->belongsToMany(
            Kasus::class,
            'kasus_lokasi',
            'lokasi_id',
            'kasus_id'
        )->withPivot('keterangan')->withTimestamps();
    }
}