<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelakuFraud extends Model
{
    use HasFactory;

    protected $table = 'pelaku_fraud';

    protected $fillable = [
        'kasus_id',
        'kategori',
        'nama',
        'jenis_identitas_id',
        'nomor_identitas',
        'jenis_kelamin',
        'alamat_identitas',
        'alamat_domisili',
        'tempat_lahir',
        'tanggal_lahir',
        'status_pelaku_id',
        'jabatan_saat_kejadian_id',
        'ket_jabatan_kejadian',
        'jabatan_saat_diketahui_id',
        'ket_jabatan_diketahui',
        'keterangan',
        'sanksi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'kategori' => 'string',
    ];

    public function getJenisKelaminLabelAttribute()
    {
        $value = strtoupper(trim((string) ($this->jenis_kelamin ?? '')));

        if ($value === 'L') {
            return 'L (Laki-laki)';
        }

        if ($value === 'P') {
            return 'P (Perempuan)';
        }

        return $value !== '' ? $value : '-';
    }

    public function getKategoriLabelAttribute()
    {
        $value = strtolower(trim((string) ($this->kategori ?? '')));

        return match ($value) {
            'internal' => '001 (Internal)',
            'eksternal' => '002 (Eksternal)',
            default => $this->kategori ?: '-',
        };
    }

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }

    public function jenisIdentitas()
    {
        return $this->belongsTo(RefJenisIdentitas::class, 'jenis_identitas_id');
    }

    public function statusPelaku()
    {
        return $this->belongsTo(RefStatusPelaku::class, 'status_pelaku_id');
    }

    public function jabatanKejadian()
    {
        return $this->belongsTo(RefJabatan::class, 'jabatan_saat_kejadian_id');
    }

    public function jabatanDiketahui()
    {
        return $this->belongsTo(RefJabatan::class, 'jabatan_saat_diketahui_id');
    }
}