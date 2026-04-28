<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKejadianFraud extends Model
{
    use HasFactory;

    protected $table = 'ref_kejadian_fraud';

    protected $fillable = ['kode', 'nama'];

    public function kasus()
    {
        return $this->belongsToMany(
            Kasus::class,
            'kasus_kejadian_fraud',
            'kejadian_id',
            'kasus_id'
        )->withTimestamps();
    }
}