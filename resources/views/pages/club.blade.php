@extends('layouts.site', ['title' => 'The Club'])

@section('content')
    <section class="hero-frame-short">
        <img src="{{ asset('images/course/wakefield-putting.jpg') }}" alt="The putting green beside the clubhouse" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-ink/50"></div>
        <div class="relative flex min-h-[52vh] flex-col justify-end px-6 pb-10 sm:px-10 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.28em] text-gold">Founded 1891 · Woodthorpe since 1911</p>
            <h1 class="mt-3 font-serif text-5xl text-white sm:text-6xl">The club</h1>
        </div>
    </section>

    <section class="mx-auto max-w-3xl py-16 lg:py-20">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">A short history</p>
        <h2 class="mt-3 font-serif text-4xl leading-tight">From the Bull Hotel to Woodthorpe Lane.</h2>
        <div class="mt-10 space-y-6 text-[17px] leading-[1.8] text-ink/80">
            <p>
                Wakefield Golf Club was founded at a meeting in the Bull Hotel, Wakefield, in August 1891.
                Nine holes were laid on Heath Common, and play began on 2 April 1892.
            </p>
            <p>
                By November 1910 the members had outgrown the common. A special general meeting approved
                a move two and a half miles to land owned by Sir Thomas Pilkington at Woodthorpe, in what
                is now the City of Wakefield. Sandy Herd was asked to design eighteen holes. The new course
                opened on 30 September 1911.
            </p>
            <p>
                The following year the club invited bunkering proposals from Herd, Fowler, Colt and MacKenzie.
                Alister MacKenzie’s fee was five guineas. His report was accepted, and the course was
                officially opened on 25 May 1912 with a 36-hole exhibition between James Braid and J.H. Taylor.
                Membership, which had stood at 278 eight months earlier, reached 430 by the end of that year.
            </p>
            <p>
                The bones of that course remain. The present 1st and 2nd absorb what was once a longer opening
                hole, and the original par-three 3rd has gone, but the two loops from the clubhouse are still
                Herd’s idea of a day’s golf in a Yorkshire park.
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-[1.75rem] bg-cream">
        <div class="grid gap-0 lg:grid-cols-3">
            @foreach ([
                ['1891', 'Founded at the Bull Hotel, Wakefield.'],
                ['1911', 'Sandy Herd’s eighteen holes open at Woodthorpe.'],
                ['1912', 'MacKenzie bunkers the course. Braid plays Taylor.'],
            ] as [$year, $note])
                <div class="border-b border-ink/10 px-8 py-12 lg:border-b-0 lg:border-r lg:last:border-r-0">
                    <p class="font-serif text-4xl text-gold">{{ $year }}</p>
                    <p class="mt-3 text-[16px] leading-relaxed text-ink/70">{{ $note }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-4 grid overflow-hidden rounded-[1.75rem] lg:grid-cols-2">
        <img src="{{ asset('images/course/wakefield-autumn.jpg') }}" alt="Autumn colour around Woodthorpe" class="h-full min-h-[400px] w-full object-cover">
        <div class="flex flex-col justify-center bg-cream px-8 py-16 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Membership</p>
            <h2 class="mt-3 font-serif text-4xl">A club for the City of Wakefield.</h2>
            <p class="mt-6 max-w-lg text-[17px] leading-relaxed text-ink/75">
                More than a century on, Wakefield remains a members’ club with a visitor welcome —
                societies, guests, and anyone who wants a proper parkland test beside Newmillerdam.
                Enquiries for membership and society days are handled from the clubhouse on Woodthorpe Lane.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('book') }}" class="btn-ink">Book a tee time</a>
                <a href="{{ route('contact') }}" class="btn-ghost">Talk to the club</a>
            </div>
        </div>
    </section>
@endsection
