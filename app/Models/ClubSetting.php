<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'opens_at',
    'closes_at',
    'season_opens_on',
    'season_closes_on',
    'interval_minutes',
    'multiple_courses',
])]
class ClubSetting extends Model
{
    private static ?self $cached = null;

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'season_opens_on' => 'date',
            'season_closes_on' => 'date',
            'interval_minutes' => 'integer',
            'multiple_courses' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return self::$cached ??= static::query()->firstOrCreate([], [
            'opens_at' => '07:00:00',
            'closes_at' => '16:00:00',
            'interval_minutes' => 10,
            'multiple_courses' => true,
        ]);
    }

    public static function forgetCache(): void
    {
        self::$cached = null;
    }

    public function opensAtLabel(): string
    {
        return self::formatTime($this->opens_at, '07:00');
    }

    public function closesAtLabel(): string
    {
        return self::formatTime($this->closes_at, '16:00');
    }

    public static function formatTime(mixed $value, string $fallback = '07:00'): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        return substr((string) $value, 0, 5);
    }
}
