<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    use HasFactory;

    protected $table = 'kasus';

    protected $fillable = [
        'user_id',
        'kode_komponen',
        'aktivitas_terkait_id',
        'deskripsi_fraud',
        'divisi_unit',
        'pihak_dirugikan_id',
        'status_penanganan',
        'jenis_laporan',
        'tindak_lanjut_ljk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelakuFrauds()
    {
        return $this->hasMany(PelakuFraud::class);
    }

    public function aktivitasTerkait()
    {
        return $this->belongsTo(RefAktivitasTerkait::class, 'aktivitas_terkait_id');
    }

    public function pihakDirugikan()
    {
        return $this->belongsTo(RefPihakDirugikan::class, 'pihak_dirugikan_id');
    }

    public function jenisFraud()
    {
        return $this->belongsToMany(
            RefJenisFraud::class,
            'kasus_jenis_fraud',
            'kasus_id',
            'jenis_fraud_id'
        )->withPivot('keterangan')->withTimestamps();
    }

    public function lokasiFraud()
    {
        return $this->belongsToMany(
            RefLokasiFraud::class,
            'kasus_lokasi',
            'kasus_id',
            'lokasi_id'
        )->withPivot('keterangan')->withTimestamps();
    }

    public function kelemahanFraud()
    {
        return $this->belongsToMany(
            RefKelemahanFraud::class,
            'kasus_kelemahan',
            'kasus_id',
            'kelemahan_id'
        )->withPivot('keterangan')->withTimestamps();
    }

    public function penangananFraud()
    {
        return $this->belongsToMany(
            RefTindakanPenanganan::class,
            'kasus_penanganan',
            'kasus_id',
            'tindakan_id'
        )->withPivot('keterangan')->withTimestamps();
    }

    public function kejadianFraud()
    {
        return $this->belongsToMany(
            RefKejadianFraud::class,
            'kasus_kejadian_fraud',
            'kasus_id',
            'kejadian_id'
        )->withPivot('kode_kejadian')->withTimestamps();
    } 

    public function waktuFraud()
    {
        return $this->hasOne(WaktuFraud::class);
    }

    public function kerugianFraud()
    {
        return $this->hasOne(KerugianFraud::class);
    }

    public function pencegahanFraud()
    {
        return $this->hasMany(PencegahanFraud::class);
    }
    
}