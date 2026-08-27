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
            @foreach ($gallery as $index => $image)
                <figure id="photo-{{ $index }}" class="mb-4 break-inside-avoid">
                    <button
                        type="button"
                        class="js-gallery-open group block w-full text-left"
                        data-index="{{ $index }}"
                        data-src="{{ asset($image['src']) }}"
                        data-alt="{{ $image['alt'] }}"
                        data-caption="{{ $image['caption'] }}"
                    >
                        <div class="media-frame">
                            <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" class="w-full object-cover">
                        </div>
                    </button>
                    <figcaption class="mt-2 text-[12px] uppercase tracking-[0.14em] text-ink/50">{{ $image['caption'] }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>

    <dialog class="js-gallery-dialog text-paper backdrop:bg-ink/80" aria-label="Photograph">
        <div class="relative flex min-h-0 flex-col">
            <button type="button" class="js-gallery-close absolute top-3 right-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-ink/70 text-paper hover:bg-gold" aria-label="Close">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
            <button type="button" class="js-gallery-prev absolute top-1/2 left-3 z-10 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-ink/70 text-paper hover:bg-gold" aria-label="Previous photograph">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M15 6 9 12l6 6"/></svg>
            </button>
            <button type="button" class="js-gallery-next absolute top-1/2 right-3 z-10 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-ink/70 text-paper hover:bg-gold" aria-label="Next photograph">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m9 6 6 6-6 6"/></svg>
            </button>
            <img class="js-gallery-image max-h-[78vh] w-full object-contain" alt="">
            <p class="js-gallery-caption px-5 py-4 text-center text-[12px] uppercase tracking-[0.16em] text-paper/70"></p>
        </div>
    </dialog>
@endsection
