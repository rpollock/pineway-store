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

    <section class="py-16 lg:py-20">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">A short history</p>
        <h2 class="mt-3 font-serif text-4xl leading-tight lg:max-w-4xl">From the Bull Hotel to Woodthorpe Lane.</h2>
        <div class="mt-10 grid gap-8 text-[17px] leading-[1.8] text-ink/80 lg:grid-cols-2 lg:gap-14">
            <div class="space-y-6">
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
            </div>
            <div class="space-y-6">
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

    <section class="py-16 lg:py-20">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">The professionals</p>
        <h2 class="mt-3 font-serif text-4xl leading-tight">The shop, the swing room, and a day’s coaching.</h2>
        <p class="mt-5 text-[17px] leading-relaxed text-ink/70">
            The professional’s shop sits beside the first tee. Adam Durie and Chris Gaunt look after fittings,
            lessons and the day-to-day running of the shop — for members, visitors and anyone who wants a
            proper look at their game.
        </p>

        <div class="mt-10 grid gap-4 lg:grid-cols-2">
            <article class="card-frame flex flex-col p-8">
                <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Head PGA professional</p>
                <h3 class="mt-3 font-serif text-3xl">Adam Durie</h3>
                <p class="mt-4 flex-1 text-[16px] leading-relaxed text-ink/70">
                    Adam runs the shop and teaches from beginner to elite — ladies, gents and juniors —
                    with video on the range and playing lessons on the course. Custom club fitting and
                    custom-fit carts are arranged from the shop.
                </p>
                <dl class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between gap-4 border-b border-ink/10 pb-2">
                        <dt class="text-ink/50">Pro shop</dt>
                        <dd><a href="tel:+441924258778" class="hover:text-gold">01924 258778, ext 2</a></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink/50">Mobile</dt>
                        <dd><a href="tel:+447885448567" class="hover:text-gold">07885 448567</a></dd>
                    </div>
                </dl>
            </article>
            <article class="card-frame flex flex-col p-8">
                <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Assistant professional</p>
                <h3 class="mt-3 font-serif text-3xl">Chris Gaunt</h3>
                <p class="mt-4 flex-1 text-[16px] leading-relaxed text-ink/70">
                    Chris looks after fittings, coaching and the shop floor. Lessons run in the swing room,
                    on the course, or online through CoachNow — useful between sessions for swing notes
                    and practice. All abilities, including anyone whose game has gone a little quiet.
                </p>
                <dl class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between gap-4 border-b border-ink/10 pb-2">
                        <dt class="text-ink/50">Pro shop</dt>
                        <dd><a href="tel:+441924258778" class="hover:text-gold">01924 258778, ext 2</a></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink/50">Mobile</dt>
                        <dd><a href="tel:+447388149308" class="hover:text-gold">07388 149308</a></dd>
                    </div>
                </dl>
            </article>
        </div>

        <div class="mt-4 grid overflow-hidden rounded-[1.75rem] bg-cream sm:grid-cols-3">
            @foreach ([
                ['The shop', 'Equipment, clothing and Wakefield embroidered kit, with fittings for ladies, gents and juniors. Brands include Ping, FootJoy, Hugo Boss, Galvin Green and Rohnisch.'],
                ['Lessons', 'Forty minutes with video from £35, an hour from £45, and junior sessions from £20. Playing lessons and blocks of six by arrangement with the shop.'],
                ['Virtual golf', 'A heated swing room with FlightScope and more than twenty-five simulated courses. Hire from £15 for half an hour — useful when the weather sits in.'],
            ] as [$title, $copy])
                <div class="border-b border-ink/10 px-8 py-10 sm:border-b-0 sm:border-r sm:last:border-r-0">
                    <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">{{ $title }}</p>
                    <p class="mt-3 text-[16px] leading-relaxed text-ink/70">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="grid overflow-hidden rounded-[1.75rem] bg-forest text-paper lg:grid-cols-2">
        <div class="px-8 py-14 lg:px-12 lg:py-16">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Location</p>
            <h2 class="mt-3 font-serif text-4xl">28 Woodthorpe Lane, Sandal.</h2>
            <p class="mt-6 text-[17px] leading-relaxed text-paper/75">
                The clubhouse is in Sandal, in the City of Wakefield, immediately beside Newmillerdam Country Park.
                From the M1, leave at junction 39, follow the A636 towards Wakefield, then signs for Sandal.
                The entrance is on Woodthorpe Lane, about 300 yards up, postcode WF2 6JH.
            </p>
            <dl class="mt-8 space-y-3 text-sm">
                <div class="flex justify-between gap-6 border-b border-paper/15 pb-3">
                    <dt class="text-paper/50">Address</dt>
                    <dd class="text-right">28 Woodthorpe Lane, Sandal, WF2 6JH</dd>
                </div>
                <div class="flex justify-between gap-6 border-b border-paper/15 pb-3">
                    <dt class="text-paper/50">Clubhouse</dt>
                    <dd><a href="tel:+441924258778" class="hover:text-gold">+44 (0) 1924 258778</a></dd>
                </div>
                <div class="flex justify-between gap-6">
                    <dt class="text-paper/50">Pro shop</dt>
                    <dd><a href="tel:+441924258778" class="hover:text-gold">Ext 2</a></dd>
                </div>
            </dl>
            <a href="{{ route('visit') }}" class="mt-8 inline-flex text-[12px] font-medium uppercase tracking-[0.2em] text-gold hover:text-paper">Visitor information →</a>
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
