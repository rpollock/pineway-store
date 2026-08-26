@extends('layouts.site')

@section('content')
    <section class="hero-frame">
        <img src="{{ asset('images/course/wakefield-hero.jpg') }}" alt="The Championship parkland at Wakefield Golf Club" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/25 to-ink/15"></div>
        <div class="relative flex min-h-[72vh] flex-col justify-end px-6 pb-12 sm:px-10 lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.32em] text-gold">England · Yorkshire · City of Wakefield</p>
            <h1 class="mt-3 max-w-3xl font-serif text-5xl leading-[0.95] text-white sm:text-7xl">Wakefield Golf Club</h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-white/80 sm:text-lg">
                A Sandy Herd parkland beside Newmillerdam, opened in 1911 and bunkered by Alister MacKenzie.
            </p>
            <a href="{{ route('book') }}" class="btn-light mt-8 self-start">Book a tee time</a>
        </div>
    </section>

    <section class="mt-4 overflow-hidden rounded-[1.75rem] bg-cream">
        <div class="grid grid-cols-2 divide-y divide-ink/10 sm:grid-cols-3 lg:grid-cols-6 lg:divide-x lg:divide-y-0">
            @foreach ([
                'Architect' => $facts['Architect'],
                'Year' => '1911',
                'Holes' => '18',
                'Par' => '72',
                'Length' => '6,653 yds',
                'Type' => 'Parkland',
            ] as $label => $value)
                <div class="px-5 py-6 lg:px-6">
                    <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-gold">{{ $label }}</p>
                    <p class="mt-2 font-serif text-xl leading-tight">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="grid gap-10 py-16 lg:grid-cols-12 lg:gap-14 lg:py-24">
        <article class="lg:col-span-8">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Course profile</p>
            <h2 class="mt-3 font-serif text-4xl leading-tight sm:text-5xl">Two loops of nine, from the clubhouse to the woods and back.</h2>
            <div class="mt-8 space-y-5 text-[17px] leading-[1.75] text-ink/80">
                <p>
                    Wakefield sits on Woodthorpe Lane in Sandal, hard against Newmillerdam Country Park.
                    The Championship course is parkland in the Yorkshire sense: mature trees, two returning nines,
                    and greens that ask for a quiet, confident putting stroke.
                </p>
                <p>
                    Alex “Sandy” Herd laid out the eighteen holes after the club left Heath Common in 1910–11.
                    The course opened for play on 30 September 1911. The following spring Alister MacKenzie
                    was engaged — for five guineas — to plan the bunkering. The official opening on 25 May 1912
                    was an exhibition between James Braid and J.H. Taylor.
                </p>
                <p>
                    The routing is still essentially Herd’s: an anti-clockwise front nine enclosing a clockwise
                    back nine, each loop starting and finishing at the clubhouse. White tees measure 6,653 yards,
                    par 72.
                </p>
            </div>
            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('book') }}" class="btn-ink">Book a tee time</a>
                <a href="{{ route('course') }}" class="btn-ghost">The course</a>
            </div>
        </article>

        <aside class="card-frame p-7 lg:col-span-4">
            <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">At a glance</p>
            <dl class="mt-6 divide-y divide-ink/10 text-sm">
                @foreach ($facts as $label => $value)
                    <div class="flex justify-between gap-4 py-3">
                        <dt class="text-ink/50">{{ $label }}</dt>
                        <dd class="text-right font-medium">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
            <a href="{{ route('book') }}" class="mt-6 block text-center btn-ink">Book a tee time</a>
            <a href="{{ route('contact') }}" class="mt-3 block text-center btn-ghost">Visitor enquiries</a>
        </aside>
    </section>

    <section class="grid overflow-hidden rounded-[1.75rem] lg:grid-cols-2">
        <img src="{{ asset('images/course/wakefield-clubhouse.jpg') }}" alt="Looking out from the clubhouse terrace" class="h-full min-h-[420px] w-full object-cover">
        <div class="flex flex-col justify-center bg-forest px-8 py-16 text-paper lg:px-14">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">The setting</p>
            <h2 class="mt-3 font-serif text-4xl leading-tight">Woodthorpe, beside the country park.</h2>
            <p class="mt-6 max-w-lg text-[17px] leading-relaxed text-paper/75">
                Sir Thomas Pilkington’s land at Woodthorpe became the club’s home after Heath Common.
                Today the course sits in the City of Wakefield, a short run south of the city centre,
                with Newmillerdam’s woodland and water just beyond the trees.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('book') }}" class="btn-light">Book a tee time</a>
                <a href="{{ route('club') }}" class="self-center text-[12px] font-medium uppercase tracking-[0.2em] text-gold hover:text-paper">Club history →</a>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-24">
        <div class="flex items-end justify-between gap-6">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Photography</p>
                <h2 class="mt-2 font-serif text-4xl">The course in pictures</h2>
            </div>
            <a href="{{ route('gallery') }}" class="hidden btn-ghost sm:inline-flex">View gallery</a>
        </div>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (array_slice($gallery, 0, 4) as $image)
                <a href="{{ route('gallery') }}" class="group block">
                    <div class="media-frame">
                        <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    </div>
                    <p class="mt-3 text-[12px] uppercase tracking-[0.14em] text-ink/55">{{ $image['caption'] }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endsection
