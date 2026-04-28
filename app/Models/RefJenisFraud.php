<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJenisFraud extends Model
{
    use HasFactory;

    protected $table = 'ref_jenis_fraud';

    protected $fillable = ['kode', 'nama'];

    public function kasus()
    {
        return $this->belongsToMany(
            Kasus::class,
            'kasus_jenis_fraud',
            'jenis_fraud_id',
            'kasus_id'
        )->withPivot('keterangan')->withTimestamps();
    }
}