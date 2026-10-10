# Contact form on the page and reduced-motion support — design

**Date:** 2026-10-10
**Status:** approved by the owner (placement, validation messages and reduced-motion detection chosen explicitly; everything else follows the existing site).
**Out of scope:** CSP, the colour palette (separate branch `feat/palette-archive-ochre`), removing the 117 unused legacy custom classes.

## A. Contact form placed in the contact card

### Decisions
- **Placement:** inside the existing `.contact-card-classic`, below the three contact buttons (email, WhatsApp, location), separated by the existing `.contact-divider-classic` and a small heading. The buttons stay.
- **Validation messages:** short custom messages per rule in Indonesian and English (required, email, max length), not Laravel's defaults.
- **Languages:** every visible string comes from `lang/id/portfolio.php` and `lang/en/portfolio.php`.

### Behaviour
- The `ContactForm` Livewire component (already written, validated, rate limited) is rendered with `<livewire:contact-form />`. Backend behaviour does not change: same rules (`ContactRules`), same service (`ContactMessageService`), same limiter (`ContactRateLimiter`).
- Fields: first name, last name (side by side from 576px up, stacked below), email, message, submit button. `maxlength` attributes mirror the rules (255, 255, 255, 5000) for convenience; the server remains the authority (`novalidate` stays).
- Feedback: a status line (sent, failed, throttled) and one error line per field, in the language of the session locale.

### Why the markup must stay free of the reveal system
`resources/css/base.css` hides `.form-group`, `.animate-on-scroll`, `.text-reveal .char` etc. (`opacity: 0`) until JavaScript adds `.reveal-active`. Livewire re-renders by morphing the DOM and restores the `class` attribute from the server, which removes JS-added classes, so fields inside the component would vanish after the first submit. Therefore the component uses **none** of those classes; the surrounding card already carries `.animate-on-scroll` and reveals as a whole.

### Language switching
- Static strings (heading, labels, button) carry `data-i18n-id` / `data-i18n-en` like the rest of the page, so the existing language button swaps them instantly.
- Strings that only appear after a server round trip (status line, field errors) are rendered in the session locale. `POST /lang/{locale}` already stores it and Livewire requests run through the `web` middleware group, so `SetLocale` applies to them.
- The `id` translations of the success, failure and throttled messages must equal `ContactMessageService::SUCCESS_MESSAGE`, `FAILURE_MESSAGE` and `ContactRateLimiter::THROTTLED_MESSAGE`, which `POST /contact` still flashes (a test guards this so the two paths cannot drift).

### Accessibility
Labels bound with `for`; `aria-invalid` and `aria-describedby` on fields with errors; the status container is always in the DOM with `role="status"` / `aria-live="polite"`; errors use `role="alert"`; the button is disabled while sending; `autocomplete` tokens as today.

### Styling
New classes in `resources/css/components/contact.css`, using the existing tokens (`--border-classic`, `--accent-emerald`, `--text-primary-classic`, ...) so dark mode works with no new colours. Inputs follow the 1.5px border and focus treatment of `.contact-btn-classic`.

### Tests (test-first)
- Livewire tests assert the messages in both locales; the translation/constant guard test; the home page renders the component; the label association test keeps passing.
- Browser check against a server started with `MAIL_MAILER=log`, `QUEUE_CONNECTION=sync` and a file cache, so a real submission writes only to the log: errors visible, success clears the form, language switch with the form present, throttling, no layout break at 375/768/1280, light and dark.

## B. `prefers-reduced-motion`

### Decisions
- Read **once at page load** (no change listener); a changed system setting applies on the next page load.

### Behaviour when the user prefers reduced motion
- **Lenis** is not started. A small stand-in exposes the same members the code uses (`stop`, `start`, `scrollTo`, `resize`, `isStopped`) on top of the browser's native scroll, so anchors, the mobile menu and the language switch keep working; anchors jump instead of gliding.
- **Scroll reveal:** observed elements get `.reveal-active .animation-finished` immediately; headings marked `.text-reveal` are not split into per-character spans.
- **Detail page (GSAP):** no tweens; elements go straight to their final state (opacity 1, no offset, timeline track at full height, dot visible).
- **CSS:** a global `@media (prefers-reduced-motion: reduce)` rule shortens transitions and animations to near-zero and turns off smooth scrolling.
- **Normal mode must not change**: verified with the same computed-style comparison used during the migration (zero differences).

### Verification of the reduced mode
`matchMedia` is stubbed before the page scripts run (the page HTML is fetched, a stub is inserted at the top of `<head>`, and the document is rewritten), then the final states above are asserted.

## Risks
- Livewire morphing versus JS-added classes (handled by the rule above).
- Language swap versus Livewire re-render: both end on the session locale, so the DOM converges; checked in the browser.
- Page height grows with the form: the layout-shift tests and a visual check at three widths cover it.
