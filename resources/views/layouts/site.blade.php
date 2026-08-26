<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $meta ?? 'Wakefield Golf Club — a Sandy Herd parkland in Sandal, City of Wakefield, opened in 1911 and bunkered by Alister MacKenzie.' }}">
        <title>{{ isset($title) ? $title.' · Wakefield Golf Club' : 'Wakefield Golf Club · City of Wakefield' }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/mark.svg') }}">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-ink antialiased">
        <header class="js-site-header site-header sticky top-0 z-40 bg-white">
            <div class="page-shell flex items-center justify-between gap-6 py-4">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <img src="{{ asset('images/mark.svg') }}" alt="" class="h-10 w-10 rounded-xl">
                    <span class="leading-tight">
                        <span class="block font-serif text-lg tracking-tight text-ink">Wakefield</span>
                        <span class="block text-[10px] font-medium uppercase tracking-[0.28em] text-gold">Golf Club · Est. 1891</span>
                    </span>
                </a>

                    <nav class="hidden items-center gap-7 text-[12px] font-medium uppercase tracking-[0.18em] text-ink/70 lg:flex">
                        @foreach ([
                            'home' => 'Overview',
                            'course' => 'The Course',
                            'club' => 'The Club',
                            'visit' => 'Visit',
                            'gallery' => 'Gallery',
                            'contact' => 'Contact',
                        ] as $name => $label)
                            <a
                                href="{{ route($name) }}"
                                class="nav-link {{ request()->routeIs($name) ? 'is-current text-ink' : 'hover:text-ink' }}"
                            >{{ $label }}</a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-3">
                        @unless (request()->routeIs('book') || request()->routeIs('book.*'))
                            <a href="{{ route('book') }}" class="btn-ink !px-4 !py-2.5">Book a tee time</a>
                        @endunless
                    <button type="button" class="js-nav-toggle inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink/15 text-ink lg:hidden" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                    </button>
                    </div>
                </div>

            <nav id="mobile-nav" class="js-mobile-nav mobile-nav page-shell lg:hidden">
                <div class="flex flex-col gap-3 rounded-2xl bg-cream px-5 py-4 text-sm font-medium uppercase tracking-[0.16em]">
                    <a href="{{ route('home') }}">Overview</a>
                    <a href="{{ route('course') }}">The Course</a>
                    <a href="{{ route('club') }}">The Club</a>
                    <a href="{{ route('visit') }}">Visit</a>
                    <a href="{{ route('gallery') }}">Gallery</a>
                    <a href="{{ route('contact') }}">Contact</a>
                    <a href="{{ route('book') }}" class="btn-ink mt-2 text-center">Book a tee time</a>
                </div>
            </nav>
        </header>

        <div class="page-shell">
            <main class="pb-8">
                @yield('content')
            </main>

            <footer class="mb-6 overflow-hidden rounded-[1.75rem] bg-forest text-paper">
                <div class="grid gap-10 px-6 py-14 sm:px-8 lg:grid-cols-4 lg:px-12">
                    <div class="lg:col-span-2">
                        <p class="font-serif text-3xl">Wakefield Golf Club</p>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-paper/70">
                            A Sandy Herd parkland beside Newmillerdam Country Park, in the City of Wakefield.
                            Eighteen holes in two loops from the clubhouse, bunkered to Alister MacKenzie’s plan of 1912.
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Visit</p>
                        <p class="mt-3 text-sm leading-relaxed text-paper/80">
                            28 Woodthorpe Lane<br>
                            Sandal<br>
                            Wakefield WF2 6JH
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Contact</p>
                        <p class="mt-3 text-sm leading-relaxed text-paper/80">
                            <a href="tel:+441924258778" class="hover:text-gold">+44 (0) 1924 258778</a><br>
                            <a href="{{ route('contact') }}" class="hover:text-gold">Enquiries</a>
                        </p>
                        <a href="{{ route('book') }}" class="btn-light mt-5">Book a tee time</a>
                    </div>
                </div>
                <div class="border-t border-paper/10 px-6 py-5 text-[11px] uppercase tracking-[0.16em] text-paper/45 sm:flex sm:justify-between sm:px-8 lg:px-12">
                    <p>© {{ date('Y') }} Wakefield Golf Club</p>
                    <p class="mt-2 sm:mt-0">City of Wakefield · West Yorkshire</p>
                </div>
            </footer>
        </div>
    </body>
</html>
