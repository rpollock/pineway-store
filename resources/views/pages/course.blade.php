@extends('layouts.site', ['title' => 'The Course'])

@section('head')
    <link rel="preload" as="image" href="{{ asset('images/course/wakefield-fairway.jpg') }}" fetchpriority="high">
@endsection

@section('content')
    <section class="hero-frame-short">
        <img src="{{ asset('images/course/wakefield-fairway.jpg') }}" alt="A tree-lined hole on the Championship course" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 bg-ink/45"></div>
        <div class="relative flex min-h-[52vh] flex-col justify-end px-6 pb-10 sm:px-10 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.28em] text-gold">Championship · Par 72 · 6,653 yards</p>
            <h1 class="mt-3 font-serif text-5xl text-white sm:text-6xl">The course</h1>
            <a href="{{ route('book') }}" class="btn-light mt-8 self-start">Book a tee time</a>
        </div>
    </section>

    <section class="grid gap-10 py-16 lg:grid-cols-12 lg:gap-14 lg:py-20">
        <article class="lg:col-span-8">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">The routing</p>
            <h2 class="mt-3 font-serif text-4xl leading-tight">An anti-clockwise nine around a clockwise nine.</h2>
            <div class="mt-8 space-y-5 text-[17px] leading-[1.75] text-ink/80">
                <p>
                    Herd’s plan still reads clearly on the ground. Both nines leave the clubhouse and return to it.
                    The front nine runs anti-clockwise and wraps the back nine, which turns clockwise through
                    the inner parkland. It is a sociable layout — you are never far from the house — and a
                    proper examination when the west wind gets into the longer two-shotters.
                </p>
                <p>
                    The short holes are the punctuation. The 2nd is a pitch of 148 yards from the white tees;
                    the 7th stretches to 220 and asks for a committed long iron. Coming home, the 16th is a
                    173-yard three that sits in the memory after a good day’s golf.
                </p>
                <p>
                    Stroke index 1 is the 11th, a 535-yard par five. The closing stretch — 17 and 18, both
                    par fours over 420 yards — is where matches are decided, with the clubhouse terrace
                    looking straight down the last.
                </p>
            </div>
            <a href="{{ route('book') }}" class="btn-ink mt-8">Book a tee time</a>
        </article>
        <aside class="lg:col-span-4">
            <div class="media-frame">
                <img src="{{ asset('images/course/wakefield-par3.jpg') }}" alt="A short hole with bunkers" class="w-full object-cover">
            </div>
            <p class="mt-3 text-[12px] uppercase tracking-[0.14em] text-ink/50">Bunkering after MacKenzie’s 1912 report</p>
        </aside>
    </section>

    <section class="rounded-[1.75rem] bg-cream px-6 py-14 sm:px-8 lg:px-12 lg:py-16">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">White tees</p>
        <h2 class="mt-2 font-serif text-4xl">Championship scorecard</h2>
        <p class="mt-3 max-w-2xl text-ink/65">Par 72 · 6,653 yards. Stroke indexes as played from the medal tees.</p>

        @php
            $out = array_slice($championship, 0, 9);
            $in = array_slice($championship, 9, 9);
            $sum = fn ($holes, $key) => array_sum(array_column($holes, $key));
        @endphp

        <div class="mt-10 overflow-x-auto rounded-2xl border border-ink/10 bg-paper">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-forest text-[11px] uppercase tracking-[0.16em] text-paper">
                    <tr>
                        <th class="px-4 py-3 font-medium">Hole</th>
                        @foreach ($out as $hole)
                            <th class="px-2 py-3 text-center font-medium">{{ $hole['number'] }}</th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-medium">Out</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-ink/10">
                        <th class="px-4 py-3 font-medium text-ink/50">Yards</th>
                        @foreach ($out as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['yards'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center font-semibold">{{ $sum($out, 'yards') }}</td>
                    </tr>
                    <tr class="border-t border-ink/10 bg-cream/60">
                        <th class="px-4 py-3 font-medium text-ink/50">Par</th>
                        @foreach ($out as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['par'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center font-semibold">{{ $sum($out, 'par') }}</td>
                    </tr>
                    <tr class="border-t border-ink/10">
                        <th class="px-4 py-3 font-medium text-ink/50">SI</th>
                        @foreach ($out as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['stroke_index'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center text-ink/40">—</td>
                    </tr>
                </tbody>
                <thead class="bg-forest text-[11px] uppercase tracking-[0.16em] text-paper">
                    <tr>
                        <th class="px-4 py-3 font-medium">Hole</th>
                        @foreach ($in as $hole)
                            <th class="px-2 py-3 text-center font-medium">{{ $hole['number'] }}</th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-medium">In</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-ink/10">
                        <th class="px-4 py-3 font-medium text-ink/50">Yards</th>
                        @foreach ($in as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['yards'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center font-semibold">{{ $sum($in, 'yards') }}</td>
                    </tr>
                    <tr class="border-t border-ink/10 bg-cream/60">
                        <th class="px-4 py-3 font-medium text-ink/50">Par</th>
                        @foreach ($in as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['par'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center font-semibold">{{ $sum($in, 'par') }}</td>
                    </tr>
                    <tr class="border-t border-ink/10">
                        <th class="px-4 py-3 font-medium text-ink/50">SI</th>
                        @foreach ($in as $hole)
                            <td class="px-2 py-3 text-center">{{ $hole['stroke_index'] }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-center text-ink/40">—</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mt-4 text-sm text-ink/55">Total {{ $sum($out, 'yards') + $sum($in, 'yards') }} yards · par {{ $sum($out, 'par') + $sum($in, 'par') }}.</p>
    </section>

    <section class="mt-4 grid overflow-hidden rounded-[1.75rem] lg:grid-cols-2">
        <div class="flex flex-col justify-center bg-cream px-8 py-16 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">A second eighteen</p>
            <h2 class="mt-3 font-serif text-4xl">The Valley course</h2>
            <p class="mt-6 max-w-lg text-[17px] leading-relaxed text-ink/75">
                Members also play the Valley — a shorter, tighter loop at around 6,270 yards, still par 72.
                It is the course for winter medals and a different kind of precision when the Championship
                is resting.
            </p>
            <a href="{{ route('book') }}" class="mt-8 self-start btn-ink">Book a tee time</a>
        </div>
        <img src="{{ asset('images/course/wakefield-green.jpg') }}" alt="A green on the Championship course" class="h-full min-h-[380px] w-full object-cover">
    </section>
@endsection
