@extends('layouts.site', ['title' => 'Visit'])

@section('content')
    <section class="hero-frame-short">
        <img src="{{ asset('images/course/wakefield-play.jpg') }}" alt="Play at Wakefield Golf Club" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-ink/50"></div>
        <div class="relative flex min-h-[52vh] flex-col justify-end px-6 pb-10 sm:px-10 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.28em] text-gold">Visitors · Societies · Guests</p>
            <h1 class="mt-3 font-serif text-5xl text-white sm:text-6xl">Come and play</h1>
            <a href="{{ route('book') }}" class="btn-light mt-8 self-start">Book a tee time</a>
        </div>
    </section>

    <section class="grid gap-4 py-10 lg:grid-cols-3">
        @foreach ([
            ['Visitors', 'Weekday and weekend visitor times can be booked online on the Championship and Valley courses, subject to club competitions. A handicap certificate or CDH number is preferred.'],
            ['Societies', 'Society days are built around two loops from the clubhouse, with breakfast, lunch or an evening meal in the dining room. Groups of twelve and above are typical.'],
            ['Guests', 'Members may introduce guests throughout the week. Please book through the professional’s shop so the tee sheet stays clear for everyone.'],
        ] as [$title, $copy])
            <article class="card-frame flex flex-col p-8">
                <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Play</p>
                <h2 class="mt-3 font-serif text-3xl">{{ $title }}</h2>
                <p class="mt-4 flex-1 text-[16px] leading-relaxed text-ink/70">{{ $copy }}</p>
                @if ($title === 'Visitors')
                    <a href="{{ route('book') }}" class="btn-ink mt-6 self-start">Book a tee time</a>
                @elseif ($title === 'Societies')
                    <a href="{{ route('contact') }}" class="btn-ghost mt-6 self-start">Plan a society day</a>
                @else
                    <a href="{{ route('book') }}" class="btn-ghost mt-6 self-start">See tee times</a>
                @endif
            </article>
        @endforeach
    </section>

    <section class="mb-4 overflow-hidden rounded-[1.75rem] bg-cream">
        <div class="px-8 pt-10 pb-4 sm:px-10">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">2026 green fees</p>
            <h2 class="mt-3 font-serif text-4xl">What it costs to play.</h2>
            <p class="mt-3 max-w-2xl text-ink/65">Payable at the professional’s shop. County cards take 25% off weekday green fees.</p>
        </div>
        <dl class="divide-y divide-ink/10">
            @foreach ($fees as $fee)
                <div class="flex items-baseline justify-between gap-6 px-8 py-4 sm:px-10">
                    <dt class="text-sm text-ink/60">{{ $fee['label'] }}</dt>
                    <dd class="font-serif text-xl">{{ $fee['value'] }}</dd>
                </div>
            @endforeach
        </dl>
        <div class="px-8 py-6 sm:px-10">
            <a href="{{ route('book') }}" class="btn-ink">Book a tee time</a>
        </div>
    </section>

    <section class="grid overflow-hidden rounded-[1.75rem] bg-forest text-paper lg:grid-cols-2">
        <div class="px-8 py-14 lg:px-12 lg:py-16">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Finding us</p>
            <h2 class="mt-3 font-serif text-4xl">28 Woodthorpe Lane, Sandal.</h2>
            <p class="mt-6 text-[17px] leading-relaxed text-paper/75">
                The clubhouse is in Sandal, in the City of Wakefield, immediately beside Newmillerdam Country Park.
                From the M1, leave at junction 39 and follow signs for Sandal and Newmillerdam.
                The entrance is on Woodthorpe Lane, postcode WF2 6JH.
            </p>
            <dl class="mt-8 space-y-3 text-sm">
                <div class="flex justify-between gap-6 border-b border-paper/15 pb-3">
                    <dt class="text-paper/50">Telephone</dt>
                    <dd><a href="tel:+441924258778" class="hover:text-gold">+44 (0) 1924 258778</a></dd>
                </div>
                <div class="flex justify-between gap-6 border-b border-paper/15 pb-3">
                    <dt class="text-paper/50">Postcode</dt>
                    <dd>WF2 6JH</dd>
                </div>
                <div class="flex justify-between gap-6">
                    <dt class="text-paper/50">Coordinates</dt>
                    <dd>53.6425, −1.485</dd>
                </div>
            </dl>
        </div>
        <div class="min-h-[320px] overflow-hidden">
            <iframe
                title="Map of Wakefield Golf Club"
                class="h-full min-h-[320px] w-full grayscale"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q=Wakefield+Golf+Club+Woodthorpe+Lane+WF2+6JH&z=14&output=embed"
            ></iframe>
        </div>
    </section>

    <section class="mx-auto max-w-3xl py-16 text-center lg:py-20">
        <h2 class="font-serif text-4xl">Book a tee time</h2>
        <p class="mx-auto mt-4 max-w-xl text-ink/65">
            Choose a day, pick a time on the Championship or Valley course, and add your group.
            Up to four players. Societies of twelve or more should still telephone the clubhouse.
        </p>
        <a href="{{ route('book') }}" class="mt-8 inline-flex btn-ink">Book online</a>
    </section>
@endsection
