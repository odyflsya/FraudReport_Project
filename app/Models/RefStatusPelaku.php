<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStatusPelaku extends Model
{
    use HasFactory;

    protected $table = 'ref_status_pelaku';

    protected $fillable = ['kode', 'nama'];
}