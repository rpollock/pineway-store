<?php

namespace App\Models;

use App\Enums\BookingType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['course_id', 'play_date', 'starts_at', 'type', 'notes', 'is_private'])]
class Booking extends Model
{
    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'play_date' => 'date',
            'type' => BookingType::class,
            'is_private' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return HasMany<BookingPlayer, $this>
     */
    public function players(): HasMany
    {
        return $this->hasMany(BookingPlayer::class)->orderBy('position');
    }

    /**
     * @return HasMany<BookingHold, $this>
     */
    public function holds(): HasMany
    {
        return $this->hasMany(BookingHold::class);
    }

    public function startsAtLabel(): string
    {
        return substr((string) $this->starts_at, 0, 5);
    }

    public function occupiedCount(): int
    {
        return $this->players->count() + $this->activeHoldSpots();
    }

    public function activeHoldSpots(): int
    {
        $holds = $this->relationLoaded('holds')
            ? $this->holds
            : $this->holds()->get();

        return (int) $holds
            ->filter(fn (BookingHold $hold) => $hold->isActive())
            ->sum('spots');
    }

    public function discardIfAbandoned(): void
    {
        if ($this->type !== BookingType::Guest || $this->is_private) {
            return;
        }

        if ($this->players()->exists() || $this->holds()->exists()) {
            return;
        }

        $this->delete();
    }
}
