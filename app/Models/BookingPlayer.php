<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'member_id', 'guest_name', 'guest_email', 'position'])]
class BookingPlayer extends Model
{
    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function label(): string
    {
        return filled($this->guest_name) ? (string) $this->guest_name : 'Guest';
    }
}
