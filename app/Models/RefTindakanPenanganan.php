<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefTindakanPenanganan extends Model
{
    use HasFactory;

    protected $table = 'ref_tindakan_penanganan';

    protected $fillable = ['kode', 'nama'];

    public function kasus()
    {
        return $this->belongsToMany(
            Kasus::class,
            'kasus_penanganan',
            'tindakan_id',
            'kasus_id'
        )->withPivot('keterangan')->withTimestamps();
    }
}