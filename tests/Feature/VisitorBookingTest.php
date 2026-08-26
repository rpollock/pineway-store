<?php

namespace Tests\Feature;

use App\Enums\BookingType;
use App\Models\Booking;
use App\Models\BookingHold;
use App\Models\BookingPlayer;
use App\Models\Course;
use Carbon\Carbon;
use Tests\TestCase;

class VisitorBookingTest extends TestCase
{
    public function test_the_book_page_renders_available_times(): void
    {
        $course = $this->course();

        $this->get('/book')
            ->assertOk()
            ->assertSee('Book a tee time')
            ->assertSee($course->name)
            ->assertSee('£60')
            ->assertSee('£80');

        $this->get('/book?date='.now()->next(Carbon::SATURDAY)->toDateString())
            ->assertOk()
            ->assertSee('£80');
    }

    public function test_a_visitor_can_hold_and_check_out_a_tee_time(): void
    {
        $course = $this->course();
        $date = now()->addDay()->toDateString();

        $this->from('/book')->post('/book/hold', [
            'course_id' => $course->id,
            'play_date' => $date,
            'starts_at' => '09:00',
            'players' => 2,
        ])->assertRedirect();

        $hold = BookingHold::query()->first();
        $this->assertNotNull($hold);
        $this->assertSame(2, $hold->spots);
        $this->assertTrue($hold->expires_at->greaterThan(now()->addMinutes(4)));

        $this->get('/book/checkout/'.$hold->token)
            ->assertOk()
            ->assertSee('Your details')
            ->assertSee('Player 2 name');

        $this->from('/book/checkout/'.$hold->token)->post('/book/checkout/'.$hold->token, [
            'name' => 'Alexander Whitmore',
            'email' => 'visitor@example.com',
            'phone' => '01924 258778',
            'companions' => ['Guest player'],
        ])->assertRedirect('/book/confirmed');

        $booking = Booking::query()->first();
        $this->assertNotNull($booking);
        $this->assertSame($course->id, $booking->course_id);
        $this->assertSame($date, $booking->play_date->toDateString());
        $this->assertSame('09:00', $booking->startsAtLabel());
        $this->assertSame(BookingType::Guest, $booking->type);
        $this->assertSame(0, BookingHold::query()->count());
        $this->assertSame(2, BookingPlayer::query()->count());
        $this->assertDatabaseHas('booking_players', [
            'guest_name' => 'Alexander Whitmore',
            'guest_email' => 'visitor@example.com',
        ]);
    }

    public function test_a_held_tee_time_is_hidden_until_it_expires(): void
    {
        $course = $this->course();
        $date = now()->addDay()->toDateString();

        $this->post('/book/hold', [
            'course_id' => $course->id,
            'play_date' => $date,
            'starts_at' => '09:00',
            'players' => 4,
        ])->assertRedirect();

        $this->get('/book?date='.$date)
            ->assertOk()
            ->assertDontSee('data-time="09:00"', false);

        $this->assertSame(1, Booking::query()->count());

        $this->travel(6)->minutes();

        $this->get('/book?date='.$date)->assertOk();
        $this->assertSame(0, BookingHold::query()->count());
        $this->assertSame(0, Booking::query()->count());
    }

    public function test_an_expired_checkout_is_released(): void
    {
        $course = $this->course();
        $date = now()->addDay()->toDateString();

        $this->post('/book/hold', [
            'course_id' => $course->id,
            'play_date' => $date,
            'starts_at' => '09:00',
            'players' => 2,
        ]);

        $hold = BookingHold::query()->first();

        $this->travel(6)->minutes();

        $this->get('/book/checkout/'.$hold->token)
            ->assertRedirect('/book?expired=1');

        $this->post('/book/checkout/'.$hold->token, [
            'name' => 'Alexander Whitmore',
            'email' => 'visitor@example.com',
            'companions' => ['Guest player'],
        ])->assertRedirect('/book?expired=1');

        $this->assertSame(0, BookingPlayer::query()->count());
    }

    public function test_a_past_tee_time_cannot_be_held(): void
    {
        $course = $this->course();

        $this->from('/book')->post('/book/hold', [
            'course_id' => $course->id,
            'play_date' => now()->toDateString(),
            'starts_at' => now()->subHour()->format('H:i'),
            'players' => 1,
        ])->assertRedirect('/book')->assertSessionHas('error');

        $this->assertSame(0, Booking::query()->count());
    }

    public function test_a_full_tee_time_cannot_be_held(): void
    {
        $course = $this->course();
        $date = now()->addDay()->toDateString();

        $booking = Booking::query()->create([
            'course_id' => $course->id,
            'play_date' => $date,
            'starts_at' => '10:00:00',
            'type' => BookingType::Member,
            'is_private' => false,
        ]);

        foreach (range(1, 4) as $position) {
            BookingPlayer::query()->create([
                'booking_id' => $booking->id,
                'guest_name' => 'Player '.$position,
                'position' => $position - 1,
            ]);
        }

        $this->from('/book')->post('/book/hold', [
            'course_id' => $course->id,
            'play_date' => $date,
            'starts_at' => '10:00',
            'players' => 1,
        ])->assertRedirect('/book')->assertSessionHas('error');
    }

    private function course(): Course
    {
        return Course::query()->create([
            'name' => 'Championship Course',
            'slug' => 'championship',
            'sort_order' => 1,
            'opens_at' => '07:00:00',
            'closes_at' => '16:00:00',
            'interval_minutes' => 10,
        ]);
    }
}
