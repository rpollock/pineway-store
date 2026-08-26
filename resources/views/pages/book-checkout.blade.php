@extends('layouts.site', ['title' => 'Checkout'])

@section('content')
    <section class="mx-auto max-w-2xl py-10 lg:py-14">
        <div
            class="js-hold-timer mb-8 flex items-center justify-between gap-4 rounded-[1.5rem] bg-cream px-5 py-4"
            data-expires="{{ $hold->expires_at->toIso8601String() }}"
            data-expired-url="{{ route('book', ['expired' => 1]) }}"
        >
            <div>
                <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-gold">Time held</p>
                <p class="mt-1 text-sm text-ink/65">Complete checkout before the hold ends or this tee time goes back on the sheet.</p>
            </div>
            <p class="js-hold-remaining font-serif text-4xl tabular-nums">5:00</p>
        </div>

        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Checkout</p>
        <h1 class="mt-3 font-serif text-5xl">Your details</h1>
        <p class="mt-4 text-ink/65">
            {{ $booking?->course?->name }} · {{ $playDate->format('l j F Y') }} · {{ $time }}
            · {{ $hold->spots }} {{ $hold->spots === 1 ? 'player' : 'players' }}
        </p>
        <p class="mt-2 font-serif text-2xl">{{ $total }} · {{ $rate }}</p>
        <p class="mt-2 text-sm text-ink/50">Payable at the professional’s shop.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl bg-cream px-5 py-4 text-sm text-ink">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('book.complete', $hold->token) }}" class="card-frame mt-10 p-8">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Your name</span>
                    <input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                </label>
                <label class="block text-sm">
                    <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                </label>
            </div>
            <label class="mt-4 block text-sm">
                <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Telephone</span>
                <input name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
            </label>

            @if ($hold->spots > 1)
                <p class="mt-8 text-[11px] uppercase tracking-[0.16em] text-gold">Other players</p>
                <div class="mt-4 space-y-4">
                    @foreach (range(2, $hold->spots) as $number)
                        <label class="block text-sm">
                            <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Player {{ $number }} name</span>
                            <input name="companions[]" value="{{ old('companions.'.($number - 2)) }}" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <button type="submit" class="btn-ink">Complete booking</button>
            </div>
        </form>

        <form method="post" action="{{ route('book.cancel', $hold->token) }}" class="mt-6">
            @csrf
            <button type="submit" class="btn-ghost">Release this time</button>
        </form>
    </section>
@endsection
