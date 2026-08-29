const toggle = document.querySelector('.js-nav-toggle');
const mobileNav = document.querySelector('.js-mobile-nav');
const header = document.querySelector('.js-site-header');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

toggle?.addEventListener('click', () => {
    const open = mobileNav?.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
});

window.addEventListener('scroll', () => {
    header?.classList.toggle('is-scrolled', window.scrollY > 8);
}, { passive: true });

document.querySelectorAll('main img').forEach((image) => {
    if (image.closest('.hero-frame, .hero-frame-short, .media-frame')) {
        return;
    }

    if (image.closest('.overflow-hidden')) {
        image.classList.add('img-zoom');
    }
});

if (!reduceMotion) {
    document.documentElement.classList.add('js-motion');

    const firstBlock = document.querySelector('main > *');
    const revealables = [
        ...document.querySelectorAll('main > *'),
        document.querySelector('footer'),
    ].filter((element) => (
        element
        && element !== firstBlock
        && !element.matches('dialog, .hero-frame, .hero-frame-short')
        && !element.querySelector('.js-pick-slot')
    ));

    revealables.forEach((element, index) => {
        element.classList.add('reveal');
        element.style.setProperty('--reveal-delay', `${Math.min(index, 4) * 70}ms`);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.01,
        rootMargin: '0px 0px -40px 0px',
    });

    document.querySelectorAll('.reveal').forEach((element) => observer.observe(element));
}

const dialog = document.querySelector('.js-book-dialog');
const courseInput = document.querySelector('.js-book-course');
const dateInput = document.querySelector('.js-book-date');
const timeInput = document.querySelector('.js-book-time');
const title = document.querySelector('.js-book-title');
const spotsLabel = document.querySelector('.js-book-spots');
const playersSelect = document.querySelector('.js-book-players');
const closeButton = document.querySelector('.js-book-close');
const totalLabel = document.querySelector('.js-book-total');

function updateTotal() {
    if (!totalLabel || !playersSelect) {
        return;
    }

    const players = Number(playersSelect.value || 1);
    const perPlayer = Number(totalLabel.dataset.price || 0);
    const fourball = Number(totalLabel.dataset.fourball || 0);
    const amount = players === 4 && fourball > 0 ? fourball : perPlayer * players;
    const rate = totalLabel.dataset.rateLabel || 'Green fee';

    totalLabel.textContent = `£${amount} · ${rate}`;
}

function bindSlotButtons(root = document) {
    root.querySelectorAll('.js-pick-slot').forEach((button) => {
        button.addEventListener('click', () => {
            const spots = Number(button.dataset.spots || 1);

            if (courseInput) courseInput.value = button.dataset.courseId || '';
            if (dateInput) dateInput.value = button.dataset.date || '';
            if (timeInput) timeInput.value = button.dataset.time || '';
            if (title) title.textContent = `${button.dataset.time} · ${button.dataset.course}`;
            if (spotsLabel) spotsLabel.textContent = `${spots} ${spots === 1 ? 'spot' : 'spots'} left on this time.`;
            if (totalLabel) {
                totalLabel.dataset.price = button.dataset.price || '0';
                totalLabel.dataset.fourball = button.dataset.fourball || '';
                totalLabel.dataset.rateLabel = button.dataset.rateLabel || '';
            }

            if (playersSelect) {
                [...playersSelect.options].forEach((option) => {
                    option.disabled = Number(option.value) > spots;
                });
                playersSelect.value = String(Math.min(Number(playersSelect.value || 1), spots));
                updateTotal();
            }

            dialog?.showModal();
        });
    });
}

bindSlotButtons();

const bookSheet = document.querySelector('.js-book-sheet');

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;');
}

function bookUrl(date, course) {
    const url = new URL(bookSheet?.dataset.url || window.location.pathname, window.location.origin);
    url.searchParams.set('date', date);
    if (course) {
        url.searchParams.set('course', String(course));
    }

    return `${url.pathname}${url.search}`;
}

function renderSlots(slots, date) {
    const grid = bookSheet?.querySelector('.js-book-slots');

    if (!grid) {
        return;
    }

    if (!slots.length) {
        grid.innerHTML = '<div class="card-frame col-span-full p-8 text-ink/65">No visitor times left on this day. Try another date, or telephone the clubhouse.</div>';
        return;
    }

    grid.innerHTML = slots.map((slot) => {
        if (!slot.available) {
            return `
                <div class="rounded-[1.5rem] border border-ink/8 bg-paper px-3 py-3 text-ink/35 sm:px-5 sm:py-5">
                    <p class="font-serif text-2xl sm:text-3xl">${escapeHtml(slot.time)}</p>
                    <p class="mt-1 font-serif text-lg sm:mt-2 sm:text-xl">${escapeHtml(slot.price_label)}</p>
                    <p class="mt-1 text-[10px] uppercase tracking-[0.1em] sm:text-[12px] sm:tracking-[0.14em]">Full</p>
                </div>
            `;
        }

        const spots = Number(slot.spots || 0);
        const spotsWord = spots === 1 ? 'spot' : 'spots';

        return `
            <button
                type="button"
                class="js-pick-slot card-frame p-3 text-left transition hover:bg-[#ebe6d8] sm:p-5"
                data-course-id="${escapeHtml(slot.course_id)}"
                data-course="${escapeHtml(slot.course)}"
                data-date="${escapeHtml(date)}"
                data-time="${escapeHtml(slot.time)}"
                data-spots="${escapeHtml(spots)}"
                data-price="${escapeHtml(slot.price)}"
                data-price-label="${escapeHtml(slot.price_label)}"
                data-rate-label="${escapeHtml(slot.rate_label)}"
                data-fourball="${escapeHtml(slot.fourball ?? '')}"
            >
                <p class="font-serif text-2xl sm:text-3xl">${escapeHtml(slot.time)}</p>
                <p class="mt-1 font-serif text-lg text-ink/80 sm:mt-2 sm:text-xl">${escapeHtml(slot.price_label)}</p>
                <p class="mt-1 text-[10px] uppercase leading-snug tracking-[0.1em] text-ink/50 sm:text-[12px] sm:tracking-[0.14em]">
                    <span class="sm:hidden">${spots} ${spotsWord}</span>
                    <span class="hidden sm:inline">${escapeHtml(slot.rate_label)} · ${spots} ${spotsWord} left</span>
                </p>
            </button>
        `;
    }).join('');

    bindSlotButtons(grid);
}

function paintBookSheet(data) {
    if (!bookSheet) {
        return;
    }

    bookSheet.dataset.date = data.selected;
    bookSheet.dataset.course = String(data.courseId ?? '');

    bookSheet.querySelectorAll('.js-book-day').forEach((day) => {
        const selected = day.dataset.date === data.selected;
        day.classList.toggle('bg-ink', selected);
        day.classList.toggle('text-paper', selected);
        day.classList.toggle('bg-cream', !selected);
        day.classList.toggle('text-ink', !selected);
        day.classList.toggle('hover:bg-cream/70', !selected);
        day.setAttribute('href', bookUrl(day.dataset.date, data.courseId));
    });

    bookSheet.querySelectorAll('.js-book-course-tab').forEach((tab) => {
        const selected = String(tab.dataset.course) === String(data.courseId);
        tab.classList.toggle('btn-ink', selected);
        tab.classList.toggle('btn-ghost', !selected);
        tab.setAttribute('href', bookUrl(data.selected, tab.dataset.course));
    });

    const month = document.querySelector('.js-book-month');
    const rate = document.querySelector('.js-book-rate');
    const rateLabel = document.querySelector('.js-book-rate-label');

    if (month) month.textContent = data.monthLabel || '';
    if (rate) rate.textContent = data.dayRate || '';
    if (rateLabel) rateLabel.textContent = data.dayRateLabel || '';

    renderSlots(data.slots || [], data.selected);
}

let bookRequest = null;

async function loadBookSheet(date, course, { push = true, fallbackHref } = {}) {
    if (!bookSheet) {
        return;
    }

    const url = bookUrl(date, course);
    bookRequest?.abort();
    bookRequest = new AbortController();

    bookSheet.classList.add('opacity-60');

    try {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            signal: bookRequest.signal,
        });

        if (!response.ok) {
            throw new Error('Could not load tee times');
        }

        const data = await response.json();
        paintBookSheet(data);

        if (push) {
            window.history.pushState({ date: data.selected, course: data.courseId }, '', url);
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            return;
        }

        window.location.href = fallbackHref || url;
    } finally {
        bookSheet.classList.remove('opacity-60');
    }
}

bookSheet?.addEventListener('click', (event) => {
    const control = event.target.closest('.js-book-day, .js-book-course-tab');

    if (!control || !bookSheet.contains(control)) {
        return;
    }

    event.preventDefault();

    const date = control.classList.contains('js-book-day')
        ? control.dataset.date
        : bookSheet.dataset.date;
    const course = control.classList.contains('js-book-course-tab')
        ? control.dataset.course
        : bookSheet.dataset.course;

    if (date === bookSheet.dataset.date && String(course) === String(bookSheet.dataset.course)) {
        return;
    }

    loadBookSheet(date, course, { fallbackHref: control.href });
});

window.addEventListener('popstate', () => {
    if (!bookSheet) {
        return;
    }

    const params = new URLSearchParams(window.location.search);
    loadBookSheet(
        params.get('date') || bookSheet.dataset.date,
        params.get('course') || bookSheet.dataset.course,
        { push: false },
    );
});

playersSelect?.addEventListener('change', updateTotal);
closeButton?.addEventListener('click', () => dialog?.close());

const galleryDialog = document.querySelector('.js-gallery-dialog');
const galleryImage = document.querySelector('.js-gallery-image');
const galleryCaption = document.querySelector('.js-gallery-caption');
const galleryItems = [...document.querySelectorAll('.js-gallery-open')];
let galleryIndex = 0;

function showGallery(index) {
    const item = galleryItems[index];

    if (! galleryDialog || ! galleryImage || ! item) {
        return;
    }

    galleryIndex = (index + galleryItems.length) % galleryItems.length;
    const current = galleryItems[galleryIndex];

    galleryImage.src = current.dataset.src || '';
    galleryImage.alt = current.dataset.alt || '';

    if (galleryCaption) {
        galleryCaption.textContent = current.dataset.caption || '';
    }

    galleryDialog.showModal();
}

galleryItems.forEach((item, index) => {
    item.addEventListener('click', () => showGallery(index));
});

document.querySelector('.js-gallery-close')?.addEventListener('click', () => galleryDialog?.close());
document.querySelector('.js-gallery-prev')?.addEventListener('click', () => showGallery(galleryIndex - 1));
document.querySelector('.js-gallery-next')?.addEventListener('click', () => showGallery(galleryIndex + 1));

galleryDialog?.addEventListener('click', (event) => {
    if (event.target === galleryDialog) {
        galleryDialog.close();
    }
});

galleryDialog?.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
        showGallery(galleryIndex - 1);
    }

    if (event.key === 'ArrowRight') {
        showGallery(galleryIndex + 1);
    }
});

if (galleryItems.length && window.location.hash.startsWith('#photo-')) {
    const index = Number(window.location.hash.replace('#photo-', ''));

    if (! Number.isNaN(index)) {
        showGallery(index);
    }
}

const holdTimer = document.querySelector('.js-hold-timer');

if (holdTimer) {
    const remaining = holdTimer.querySelector('.js-hold-remaining');
    const expires = Date.parse(holdTimer.dataset.expires || '');
    const expiredUrl = holdTimer.dataset.expiredUrl || '/book?expired=1';

    const tick = () => {
        const seconds = Number.isNaN(expires) ? 0 : Math.max(0, Math.ceil((expires - Date.now()) / 1000));
        const minutes = Math.floor(seconds / 60);
        const leftover = String(seconds % 60).padStart(2, '0');

        if (remaining) {
            remaining.textContent = `${minutes}:${leftover}`;
        }

        if (seconds <= 0) {
            window.location.href = expiredUrl;
        }
    };

    tick();
    window.setInterval(tick, 1000);
}
