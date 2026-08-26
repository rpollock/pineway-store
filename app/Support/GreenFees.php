<?php

namespace App\Support;

use App\Models\ClubSetting;
use Carbon\Carbon;

class GreenFees
{
    public const SUMMER_MIDWEEK = 60;

    public const SUMMER_WEEKEND = 80;

    public const WINTER_MIDWEEK = 35;

    public const WINTER_WEEKEND = 45;

    public const TWILIGHT = 40;

    public const WITH_MEMBER = 35;

    public const JUNIOR = 15;

    public const FOURBALL_SUMMER = 170;

    public const FOURBALL_WINTER = 110;

    public const BUGGY_ROUND = 35;

    public const BUGGY_DAY = 50;

    public static function isSummer(Carbon $date): bool
    {
        $settings = ClubSetting::current();

        if ($settings->season_opens_on && $settings->season_closes_on) {
            return $date->betweenIncluded($settings->season_opens_on, $settings->season_closes_on);
        }

        return $date->month >= 4 && $date->month <= 10;
    }

    public static function isTwilight(Carbon $date, string $time): bool
    {
        $cutoff = $date->isSunday() ? '15:00' : '16:00';

        return $time >= $cutoff;
    }

    public static function perPlayer(Carbon $date, ?string $time = null): int
    {
        if ($time && self::isTwilight($date, $time)) {
            return self::TWILIGHT;
        }

        if (self::isSummer($date)) {
            return $date->isWeekend() ? self::SUMMER_WEEKEND : self::SUMMER_MIDWEEK;
        }

        return $date->isWeekend() ? self::WINTER_WEEKEND : self::WINTER_MIDWEEK;
    }

    public static function fourball(Carbon $date, ?string $time = null): ?int
    {
        if ($date->isWeekend() || ($time && self::isTwilight($date, $time))) {
            return null;
        }

        return self::isSummer($date) ? self::FOURBALL_SUMMER : self::FOURBALL_WINTER;
    }

    public static function total(Carbon $date, int $players, ?string $time = null): int
    {
        $players = max(1, min(4, $players));
        $fourball = self::fourball($date, $time);

        if ($players === 4 && $fourball !== null) {
            return $fourball;
        }

        return self::perPlayer($date, $time) * $players;
    }

    public static function label(Carbon $date, ?string $time = null): string
    {
        if ($time && self::isTwilight($date, $time)) {
            return 'Twilight';
        }

        $season = self::isSummer($date) ? 'Summer' : 'Winter';
        $day = $date->isWeekend() ? 'weekend' : 'midweek';

        return $season.' '.$day;
    }

    public static function format(int $amount): string
    {
        return '£'.$amount;
    }

    /**
     * @return list<array{label: string, value: string}>
     */
    public static function table(): array
    {
        return [
            ['label' => '18 holes · midweek summer', 'value' => self::format(self::SUMMER_MIDWEEK)],
            ['label' => '18 holes · weekend summer', 'value' => self::format(self::SUMMER_WEEKEND)],
            ['label' => '18 holes · midweek winter', 'value' => self::format(self::WINTER_MIDWEEK)],
            ['label' => '18 holes · weekend winter', 'value' => self::format(self::WINTER_WEEKEND)],
            ['label' => 'Twilight · after 4pm (3pm Sunday)', 'value' => self::format(self::TWILIGHT)],
            ['label' => 'Playing with a member', 'value' => self::format(self::WITH_MEMBER)],
            ['label' => 'Juniors', 'value' => self::format(self::JUNIOR)],
            ['label' => 'Fourball offer · midweek summer', 'value' => self::format(self::FOURBALL_SUMMER)],
            ['label' => 'Fourball offer · midweek winter', 'value' => self::format(self::FOURBALL_WINTER)],
            ['label' => 'Buggy hire · per round / day', 'value' => self::format(self::BUGGY_ROUND).' / '.self::format(self::BUGGY_DAY)],
        ];
    }
}
