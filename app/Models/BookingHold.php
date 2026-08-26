<?php

namespace App\Models;

use App\Enums\BookingType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

#[Fillable(['booking_id', 'token', 'spots', 'expires_at'])]
class BookingHold extends Model
{
    public const MINUTES = 5;

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at?->isFuture() ?? false;
    }

    public static function releaseExpired(): void
    {
        static::query()
            ->where('expires_at', '<=', now())
            ->get()
            ->each(function (self $hold): void {
                $booking = $hold->booking;
                $hold->delete();
                $booking?->discardIfAbandoned();
            });
    }

    public static function findActive(string $token): ?self
    {
        self::releaseExpired();

        $hold = static::query()->where('token', $token)->first();

        return $hold?->isActive() ? $hold : null;
    }

    public static function place(int $courseId, string $playDate, string $startsAt, int $spots): self
    {
        $spots = max(1, min(4, $spots));
        $time = strlen($startsAt) === 5 ? $startsAt.':00' : $startsAt;

        return DB::transaction(function () use ($courseId, $playDate, $time, $spots) {
            self::releaseExpired();

            $booking = Booking::query()
                ->where('course_id', $courseId)
                ->whereDate('play_date', $playDate)
                ->where('starts_at', $time)
                ->lockForUpdate()
                ->first();

            if ($booking && in_array($booking->type, [BookingType::Blocked, BookingType::Competition], true)) {
                throw new RuntimeException('This tee time is not available.');
            }

            if ($booking?->is_private) {
                throw new RuntimeException('This tee time is not available.');
            }

            if (! $booking) {
                $booking = Booking::query()->create([
                    'course_id' => $courseId,
                    'play_date' => $playDate,
                    'starts_at' => $time,
                    'type' => BookingType::Guest,
                    'is_private' => false,
                ]);
            }

            $booking->load(['players', 'holds']);

            if ($booking->occupiedCount() + $spots > 4) {
                throw new RuntimeException('This tee time does not have enough space.');
            }

            return static::query()->create([
                'booking_id' => $booking->id,
                'token' => Str::random(48),
                'spots' => $spots,
                'expires_at' => now()->addMinutes(self::MINUTES),
            ]);
        });
    }

    public function release(): void
    {
        $booking = $this->booking;
        $this->delete();
        $booking?->discardIfAbandoned();
    }
}
