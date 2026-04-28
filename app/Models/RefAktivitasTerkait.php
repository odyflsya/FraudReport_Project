<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefAktivitasTerkait extends Model
{
    use HasFactory;

    protected $table = 'ref_aktivitas_terkait';

    protected $fillable = ['kode', 'nama'];
}