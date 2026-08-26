@extends('layouts.site', ['title' => 'Gallery'])

@section('content')
    <section class="py-10 lg:py-14">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-gold">Wakefield Golf Club</p>
        <h1 class="mt-3 font-serif text-5xl">Gallery</h1>
        <p class="mt-4 max-w-2xl text-ink/65">
            Original editorial photographs of the Woodthorpe parkland — the two loops, the short holes,
            and the clubhouse looking down the opening fairway.
        </p>
        <a href="{{ route('book') }}" class="btn-ink mt-6">Book a tee time</a>

        <div class="mt-12 columns-1 gap-4 sm:columns-2 lg:columns-3">
            @foreach ($gallery as $image)
                <figure class="mb-4 break-inside-avoid">
                    <div class="media-frame">
                        <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" class="w-full object-cover">
                    </div>
                    <figcaption class="mt-2 text-[12px] uppercase tracking-[0.14em] text-ink/50">{{ $image['caption'] }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endsection
