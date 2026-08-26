<?php

namespace Tests;

use App\Models\ClubSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->ensureBookingTables();
        ClubSetting::forgetCache();
    }

    protected function ensureBookingTables(): void
    {
        if (Schema::hasTable('courses')) {
            return;
        }

        Schema::create('club_settings', function (Blueprint $table): void {
            $table->id();
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->date('season_opens_on')->nullable();
            $table->date('season_closes_on')->nullable();
            $table->unsignedTinyInteger('interval_minutes')->default(10);
            $table->boolean('multiple_courses')->default(true);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->unsignedTinyInteger('interval_minutes')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('play_date');
            $table->time('starts_at');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->boolean('is_private')->default(false);
            $table->timestamps();
            $table->unique(['course_id', 'play_date', 'starts_at']);
        });

        Schema::create('booking_holds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->unsignedTinyInteger('spots');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('booking_players', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });
    }
}
