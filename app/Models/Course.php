<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'sort_order', 'opens_at', 'closes_at', 'interval_minutes'])]
class Course extends Model
{
    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'interval_minutes' => 'integer',
        ];
    }

    /**
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function intervalMinutes(): int
    {
        return (int) ($this->interval_minutes ?: ClubSetting::current()->interval_minutes ?: 10);
    }
}
