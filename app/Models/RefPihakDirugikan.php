<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefPihakDirugikan extends Model
{
    use HasFactory;

    protected $table = 'ref_pihak_dirugikan';

    protected $fillable = ['kode', 'nama'];
}