<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerugianFraud extends Model
{
    use HasFactory;

    protected $table = 'kerugian_fraud';

    protected $fillable = [
        'kasus_id',
        'ljk_rill',
        'ljk_potensial',
        'ljk_recovery',
        'konsumen_rill',
        'konsumen_potensial',
        'konsumen_recovery',
        'pihak_lain_rill',
        'pihak_lain_potensial',
        'pihak_lain_recovery',
    ];

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }

    public function recoveries()
    {
        return $this->hasMany(KerugianRecovery::class);
    }

    public function details()
    {
        return $this->hasMany(KerugianDetail::class);
    }

    public function getRecoveryTotalForKategori(string $kategori): float
    {
        if ($this->relationLoaded('recoveries')) {
            return (float) $this->recoveries->where('kategori', $kategori)->sum('amount');
        }

        return (float) $this->recoveries()->where('kategori', $kategori)->sum('amount');
    }

    public function getTotalRecovery(): float
    {
        if ($this->relationLoaded('recoveries') && $this->recoveries->isNotEmpty()) {
            return (float) $this->recoveries->sum('amount');
        }

        return (float) $this->recoveries()->sum('amount');
    }

    public function getGrossLoss(): float
    {
        return (float) (($this->ljk_rill ?? 0) + ($this->ljk_potensial ?? 0)
            + ($this->konsumen_rill ?? 0) + ($this->konsumen_potensial ?? 0)
            + ($this->pihak_lain_rill ?? 0) + ($this->pihak_lain_potensial ?? 0));
    }

    /** Kerugian tersisa per kategori: (Riil + Potensial) - Total Recovery kategori */
    public function getOutstandingForKategori(string $kategori): float
    {
        $rill = (float) ($this->{$kategori . '_rill'} ?? 0);
        $potensial = (float) ($this->{$kategori . '_potensial'} ?? 0);
        $recovery = $this->getRecoveryTotalForKategori($kategori);

        return max(0, $rill + $potensial - $recovery);
    }

    public function getTotalOutstanding(): float
    {
        return max(0, $this->getGrossLoss() - $this->getTotalRecovery());
    }

    /** Histori recovery dengan total kerugian berjalan setelah setiap entry. */
    public function getRecoveryHistoryWithRunningTotals(): array
    {
        $gross = $this->getGrossLoss();
        $cumulative = 0;
        $rows = [];

        $recoveries = $this->relationLoaded('recoveries')
            ? $this->recoveries->sortBy(fn ($r) => $r->tanggal ?? $r->created_at)
            : $this->recoveries()->orderBy('tanggal')->orderBy('created_at')->get();

        foreach ($recoveries as $recovery) {
            $cumulative += (float) $recovery->amount;
            $rows[] = [
                'id' => $recovery->id,
                'tanggal' => $recovery->tanggal ?? $recovery->created_at,
                'kategori' => $recovery->kategori,
                'amount' => (float) $recovery->amount,
                'keterangan' => $recovery->keterangan,
                'no_rekening' => $recovery->no_rekening ?? null,
                'total_outstanding' => max(0, $gross - $cumulative),
            ];
        }

        return $rows;
    }
}
