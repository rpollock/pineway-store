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

document.querySelector('.hero-frame > .relative, .hero-frame-short > .relative')?.classList.add('hero-copy');

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

    const revealables = [
        ...document.querySelectorAll('main > *'),
        document.querySelector('footer'),
    ].filter((element) => (
        element
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

document.querySelectorAll('.js-pick-slot').forEach((button) => {
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

playersSelect?.addEventListener('change', updateTotal);
closeButton?.addEventListener('click', () => dialog?.close());

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
