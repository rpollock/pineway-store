@extends('layouts.site', ['title' => 'Contact'])

@section('content')
    <section class="grid gap-10 py-10 lg:grid-cols-12 lg:gap-16 lg:py-16">
        <div class="lg:col-span-5">
            <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">The clubhouse</p>
            <h1 class="mt-3 font-serif text-5xl leading-tight">Write to us, or come down Woodthorpe Lane.</h1>
            <p class="mt-6 text-[17px] leading-relaxed text-ink/70">
                The office and professional’s shop sit beside the first tee.
                Visitor tee times can be booked online. Use the form for society days and membership, or telephone the clubhouse.
            </p>
            <a href="{{ route('book') }}" class="btn-ink mt-8">Book a tee time</a>
            <dl class="mt-10 space-y-5 text-sm">
                <div>
                    <dt class="text-[11px] uppercase tracking-[0.18em] text-gold">Address</dt>
                    <dd class="mt-1 leading-relaxed">28 Woodthorpe Lane, Sandal, Wakefield, WF2 6JH</dd>
                </div>
                <div>
                    <dt class="text-[11px] uppercase tracking-[0.18em] text-gold">Telephone</dt>
                    <dd class="mt-1"><a href="tel:+441924258778" class="hover:text-gold">+44 (0) 1924 258778</a></dd>
                </div>
                <div>
                    <dt class="text-[11px] uppercase tracking-[0.18em] text-gold">Official club</dt>
                    <dd class="mt-1"><a href="https://www.wakefieldgolfclub.co.uk" class="hover:text-gold" target="_blank" rel="noreferrer">wakefieldgolfclub.co.uk</a></dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-7">
            @if (session('enquiry_sent'))
                <div class="mb-4 rounded-2xl border border-forest/20 bg-cream px-6 py-5 text-sm text-forest">
                    Thank you. The clubhouse has your enquiry and will be in touch.
                </div>
            @endif

            <form method="post" action="{{ route('contact.send') }}" class="space-y-5 rounded-[1.75rem] bg-cream p-8">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm">
                        <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Name</span>
                        <input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                        @error('name') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                    </label>
                    <label class="block text-sm">
                        <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Email</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                        @error('email') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                    </label>
                </div>
                <label class="block text-sm">
                    <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Subject</span>
                    <select name="subject" class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">
                        @foreach (['Visitor tee time', 'Society day', 'Membership', 'General enquiry'] as $option)
                            <option @selected(old('subject') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm">
                    <span class="text-[11px] uppercase tracking-[0.16em] text-ink/50">Message</span>
                    <textarea name="message" rows="6" required class="mt-2 w-full rounded-xl border border-ink/15 bg-paper px-3 py-2.5 outline-none focus:border-gold">{{ old('message') }}</textarea>
                    @error('message') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <button type="submit" class="btn-ink">Send enquiry</button>
            </form>
        </div>
    </section>
@endsection
