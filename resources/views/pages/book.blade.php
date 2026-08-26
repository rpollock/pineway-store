@extends('layouts.site', ['title' => 'Book a tee time'])

@section('content')
    <section class="py-8 lg:py-10">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Visitors</p>
        <h1 class="mt-3 font-serif text-5xl">Book a tee time</h1>
        <p class="mt-4 max-w-2xl text-ink/65">
            Choose a day and a time on the Championship or Valley course.
            Green fees are {{ \App\Support\GreenFees::format($dayRate) }} today ({{ strtolower($dayRateLabel) }}), payable at the professional’s shop.
        </p>
    </section>

    <section class="mb-8 overflow-hidden rounded-[1.75rem] bg-cream">
        <div class="grid gap-0 sm:grid-cols-2 lg:grid-cols-5">
            @foreach (array_slice($fees, 0, 5) as $fee)
                <div class="border-b border-ink/10 px-5 py-5 sm:border-r sm:last:border-r-0 lg:border-b-0">
                    <p class="text-[10px] font-medium uppercase tracking-[0.18em] text-gold">{{ $fee['label'] }}</p>
                    <p class="mt-2 font-serif text-2xl">{{ $fee['value'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    @if (($error ?? session('error')) || $errors->any())
        <div class="mb-6 rounded-2xl bg-cream px-5 py-4 text-sm text-ink">
            {{ $error ?? session('error') ?? $errors->first() }}
        </div>
    @endif

    @if ($courses->isEmpty())
        <div class="card-frame mb-10 p-8 text-ink/70">The tee sheet is being prepared. Please telephone the clubhouse on +44 (0) 1924 258778.</div>
    @else
        <div class="mb-4 flex flex-wrap gap-2">
            @foreach ($courses as $course)
                <a
                    href="{{ route('book', ['date' => $selected, 'course' => $course->id]) }}"
                    class="{{ (int) $courseId === (int) $course->id ? 'btn-ink' : 'btn-ghost' }}"
                >{{ $course->name }}</a>
            @endforeach
        </div>

        <div class="mb-6 flex gap-2 overflow-x-auto pb-1">
            @foreach ($days as $day)
                <a
                    href="{{ route('book', ['date' => $day['date'], 'course' => $courseId]) }}"
                    class="flex min-w-[4.4rem] flex-col items-center rounded-2xl px-3 py-3 text-center {{ $selected === $day['date'] ? 'bg-ink text-paper' : 'bg-cream text-ink hover:bg-cream/70' }}"
                >
                    <span class="text-[10px] font-medium uppercase tracking-[0.16em] opacity-70">{{ $day['label'] }}</span>
                    <span class="mt-1 font-serif text-2xl leading-none">{{ $day['day'] }}</span>
                    <span class="mt-1 text-[10px] uppercase tracking-[0.14em] opacity-60">{{ $day['month'] }}</span>
                </a>
            @endforeach
        </div>

        <p class="mb-4 text-sm text-ink/55">{{ $monthLabel }}</p>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($slots as $slot)
                @if ($slot['available'])
                    <button
                        type="button"
                        class="js-pick-slot card-frame p-5 text-left transition hover:bg-[#ebe6d8]"
                        data-course-id="{{ $slot['course_id'] }}"
                        data-course="{{ $slot['course'] }}"
                        data-date="{{ $selected }}"
                        data-time="{{ $slot['time'] }}"
                        data-spots="{{ $slot['spots'] }}"
                        data-price="{{ $slot['price'] }}"
                        data-price-label="{{ $slot['price_label'] }}"
                        data-rate-label="{{ $slot['rate_label'] }}"
                        data-fourball="{{ $slot['fourball'] ?? '' }}"
                    >
                        <p class="font-serif text-3xl">{{ $slot['time'] }}</p>
                        <p class="mt-2 font-serif text-xl text-ink/80">{{ $slot['price_label'] }}</p>
                        <p class="mt-1 text-[12px] uppercase tracking-[0.14em] text-ink/50">
                            {{ $slot['rate_label'] }} · {{ $slot['spots'] }} {{ $slot['spots'] === 1 ? 'spot' : 'spots' }} left
                        </p>
                    </button>
                @else
                    <div class="rounded-[1.5rem] border border-ink/8 bg-paper px-5 py-5 text-ink/35">
                        <p class="font-serif text-3xl">{{ $slot['time'] }}</p>
                        <p class="mt-2 font-serif text-xl">{{ $slot['price_label'] }}</p>
                        <p class="mt-1 text-[12px] uppercase tracking-[0.14em]">Full</p>
                    </div>
                @endif
            @empty
                <div class="card-frame col-span-full p-8 text-ink/65">
                    No visitor times left on this day. Try another date, or telephone the clubhouse.
                </div>
            @endforelse
        </div>
    @endif

    <dialog class="js-book-dialog rounded-[1.75rem] bg-paper p-0 text-ink backdrop:bg-ink/45">
        <form method="post" action="{{ route('book.hold') }}" class="p-8">
            @csrf
            <input type="hidden" name="course_id" class="js-book-course">
            <input type="hidden" name="play_date" class="js-book-date">
            <input type="hidden" name="starts_at" class="js-book-time">

            <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-gold">Book this tee time</p>
            <h2 class="js-book-title mt-2 font-serif text-3xl">Tee time</h2>
            <p class="js-book-spots mt-2 text-sm text-ink/55"></p>
            <p class="js-book-total mt-1 font-serif text-2xl"></p>
            <p class="mt-3 text-sm text-ink/55">We’ll hold the time for five minutes while you check out.</p>

            <label class="mt-6 block text-sm">
                <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">How many people</span>
                <select name="players" class="js-book-players mt-2 w-full rounded-xl border border-ink/15 bg-cream px-3 py-2.5 outline-none focus:border-gold">
                    <option value="1">1 player</option>
                    <option value="2">2 players</option>
                    <option value="3">3 players</option>
                    <option value="4">4 players</option>
                </select>
            </label>

            <div class="mt-8 flex flex-wrap gap-3">
                <button type="submit" class="btn-ink">Confirm booking</button>
                <button type="button" class="js-book-close btn-ghost">Cancel</button>
            </div>
        </form>
    </dialog>
@endsection
