<?php

namespace App\Support;

use App\Enums\BookingType;
use App\Models\Booking;
use App\Models\BookingHold;
use App\Models\ClubSetting;
use App\Models\Course;
use App\Support\GreenFees;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TeeSheet
{
    /**
     * @return list<string>
     */
    public static function times(?Course $course = null): array
    {
        $settings = ClubSetting::current();
        $open = ClubSetting::formatTime($course?->opens_at, $settings->opensAtLabel());
        $close = ClubSetting::formatTime($course?->closes_at, $settings->closesAtLabel());
        $interval = (int) ($course?->interval_minutes ?: $settings->interval_minutes ?: 10);

        if ($interval < 1) {
            $interval = 10;
        }

        $times = [];
        $cursor = Carbon::parse('2000-01-01 '.$open);
        $end = Carbon::parse('2000-01-01 '.$close);

        if ($end->lte($cursor)) {
            return [$open];
        }

        while ($cursor <= $end) {
            $times[] = $cursor->format('H:i');
            $cursor->addMinutes($interval);
        }

        return $times;
    }

    /**
     * @return Collection<int, object{time: string, booking: ?Booking}>
     */
    public static function slots(Course $course, Carbon $date): Collection
    {
        BookingHold::releaseExpired();

        $bookings = Booking::query()
            ->with(['players', 'holds'])
            ->where('course_id', $course->id)
            ->whereDate('play_date', $date)
            ->get()
            ->keyBy(fn (Booking $booking) => $booking->startsAtLabel());

        return collect(self::times($course))
            ->merge($bookings->keys())
            ->unique()
            ->sort()
            ->values()
            ->map(fn (string $time) => (object) [
                'time' => $time,
                'booking' => $bookings->get($time),
            ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function visitorSlots(Carbon $date, ?int $courseId = null): array
    {
        $settings = ClubSetting::current();
        $courses = Course::query()->orderBy('sort_order')->orderBy('name')->get();

        if (! $settings->multiple_courses) {
            $courses = $courses->take(1);
        }

        if ($courseId) {
            $courses = $courses->where('id', $courseId)->values();
        }

        $rows = [];
        $now = now();

        foreach ($courses as $course) {
            foreach (self::slots($course, $date) as $slot) {
                if ($date->copy()->setTimeFromTimeString($slot->time)->lte($now)) {
                    continue;
                }

                $booking = $slot->booking;
                $occupied = $booking?->occupiedCount() ?? 0;
                $closed = $booking && in_array($booking->type, [BookingType::Blocked, BookingType::Competition], true);
                $private = (bool) ($booking?->is_private);
                $spots = ($closed || $private) ? 0 : max(0, 4 - $occupied);

                $price = GreenFees::perPlayer($date, $slot->time);

                $rows[] = [
                    'time' => $slot->time,
                    'course_id' => $course->id,
                    'course' => $course->name,
                    'course_slug' => $course->slug,
                    'occupied' => 4 - $spots,
                    'spots' => $spots,
                    'available' => $spots > 0,
                    'price' => $price,
                    'price_label' => GreenFees::format($price),
                    'rate_label' => GreenFees::label($date, $slot->time),
                    'fourball' => GreenFees::fourball($date, $slot->time),
                ];
            }
        }

        usort($rows, fn (array $a, array $b) => [$a['time'], $a['course']] <=> [$b['time'], $b['course']]);

        return $rows;
    }
}
