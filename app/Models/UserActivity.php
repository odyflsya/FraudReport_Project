<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'role',
        'activity',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const LEGACY_EXPORT_ACTIVITIES = [
        'Download Laporan',
        'Export Excel',
    ];

    public static function normalizeActivity(?string $activity): ?string
    {
        if ($activity === null) {
            return null;
        }

        if (in_array($activity, self::LEGACY_EXPORT_ACTIVITIES, true)) {
            return 'Export Laporan';
        }

        return $activity;
    }

    public static function activityFilterValues(string $activity): array
    {
        if ($activity === 'Export Laporan') {
            return array_merge(['Export Laporan'], self::LEGACY_EXPORT_ACTIVITIES);
        }

        return [$activity];
    }

    public static function distinctActivityTypes()
    {
        return static::query()
            ->distinct()
            ->orderBy('activity')
            ->pluck('activity')
            ->map(fn (string $activity) => self::normalizeActivity($activity))
            ->unique()
            ->sort()
            ->values();
    }

    public function getDisplayActivityAttribute(): string
    {
        return self::normalizeActivity($this->activity) ?? '';
    }
}
