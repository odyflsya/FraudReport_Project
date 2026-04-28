<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJenisIdentitas extends Model
{
    use HasFactory;

    protected $table = 'ref_jenis_identitas';

    protected $fillable = ['kode', 'nama'];
}