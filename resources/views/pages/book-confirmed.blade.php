@extends('layouts.site', ['title' => 'Booking confirmed'])

@section('content')
    <section class="mx-auto max-w-2xl py-16 text-center lg:py-24">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">You’re on the sheet</p>
        <h1 class="mt-3 font-serif text-5xl">Tee time booked</h1>
        <p class="mt-4 text-ink/65">
            {{ $confirmation['course'] }} · {{ $confirmation['date'] }} · {{ $confirmation['time'] }}
        </p>
        <div class="card-frame mx-auto mt-10 max-w-md p-8 text-left">
            <p class="text-[11px] uppercase tracking-[0.16em] text-gold">Players</p>
            <ul class="mt-3 space-y-2 text-[16px]">
                @foreach ($confirmation['players'] as $player)
                    <li>{{ $player }}</li>
                @endforeach
            </ul>
            @if (! empty($confirmation['total']))
                <div class="mt-6 flex items-baseline justify-between border-t border-ink/10 pt-5">
                    <p class="text-[11px] uppercase tracking-[0.16em] text-gold">{{ $confirmation['rate'] ?? 'Green fee' }}</p>
                    <p class="font-serif text-3xl">{{ $confirmation['total'] }}</p>
                </div>
            @endif
            <p class="mt-6 text-sm text-ink/55">Please arrive 20 minutes before your time and pay at the professional’s shop. Confirmation is held against {{ $confirmation['email'] }}.</p>
        </div>
        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('book') }}" class="btn-ink">Book another time</a>
            <a href="{{ route('visit') }}" class="btn-ghost">Back to visit</a>
        </div>
    </section>
@endsection
