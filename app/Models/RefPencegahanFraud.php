<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPencegahanFraud extends Model
{
    use HasFactory;

    protected $table = 'ref_pencegahan_fraud';

    protected $fillable = ['kode', 'nama'];

    public function pencegahanFraudRecords()
    {
        return $this->hasMany(PencegahanFraud::class, 'pencegahan_id');
    }
}