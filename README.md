# zen-porto

Personal portfolio site for Zenifen Caesarof Agusti, built with Laravel 12, Blade, Tailwind CSS, Alpine.js (the copy bundled with Livewire) and Vite, plus GSAP and Lenis for animation and smooth scrolling. Livewire is used for exactly one component, the contact form. The site has a bilingual (Indonesian/English) home page, project detail pages, a contact endpoint (and the Livewire `ContactForm` component, not placed on a page yet) that deliver mail through the queue, and a small read-only JSON API.

> This README lists environment variables **by name only**. Never put real values (keys, passwords, tokens, addresses) in this file, in issues, or in commits. `.env` is git-ignored; `.env.example` is the template and must contain placeholders only.

## Requirements

- PHP 8.2+ with the extensions Laravel 12 needs (and `pdo_sqlite` for the test suite)
- Composer
- Node.js (a version supported by Vite 7) and npm
- A database supported by Laravel (the template uses MySQL; SQLite works for local use)

`composer.json` pins `config.platform.php` to `8.2.0`, so dependencies are resolved to stay installable on PHP 8.2 even when you develop on a newer PHP (some Symfony 8 components need PHP 8.4). Raise the pin deliberately when production moves to a newer PHP.

## Local setup

```bash
composer install
cp .env.example .env          # then fill in values locally; never commit .env
php artisan key:generate      # local key only; never reuse a production key
php artisan migrate --seed    # creates tables (incl. jobs, failed_jobs, cache, sessions) and seeds projects
npm ci
npm run build                 # or `npm run dev` while developing
```

`composer run setup` performs most of these steps in one go. `composer run dev` starts the web server, a queue listener and the Vite dev server together.

### Running the tests

```bash
php artisan test
```

> **Build the front-end assets first.** The tests render real pages, and the `@vite` directive reads `public/build/manifest.json`. `public/build` is Git-ignored, so on a fresh clone run `npm ci && npm run build` before `php artisan test` (or `composer test`); without it every test that renders a page fails with `ViteManifestNotFoundException`. `composer run setup` already builds them.

`phpunit.xml` provides everything else the suite needs, so tests do not depend on a local `.env`:

- an in-memory SQLite database, `array` cache/session/mail drivers and the `sync` queue;
- a **test-only** `APP_KEY`, randomly generated for the suite (it is not, and must never be, used by a real environment);
- a placeholder `CONTACT_RECIPIENT_EMAIL` on the reserved `example.test` domain.

Values in `phpunit.xml` take precedence over `.env`. Except for `CACHE_STORE` and `SESSION_DRIVER` (forced to `array` so query-count tests stay exact), they do not override a variable that is already exported in the process environment (for example by a CI runner).

## Environment variables

Set these in `.env` locally and in the hosting provider's secret/environment settings in production. Names only:

| Area | Variables | Notes |
| --- | --- | --- |
| Application | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `APP_LOCALE`, `APP_FALLBACK_LOCALE` | `APP_DEBUG` must be false in production. `APP_KEY` encrypts cookies, sessions and queued contact mail. `APP_PREVIOUS_KEYS` is only used during a key rotation. |
| Database | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | |
| Session / cache | `SESSION_DRIVER`, `SESSION_LIFETIME`, `SESSION_ENCRYPT`, `CACHE_STORE` | Rate limiters use the default cache store. |
| Queue | `QUEUE_CONNECTION` | Defaults to `database` in `config/queue.php`. See [Queue](#queue). |
| Mail | `MAIL_MAILER`, `MAIL_SCHEME`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | |
| Contact | `CONTACT_RECIPIENT_EMAIL` | Where contact messages go. Defaults to empty: if unset, every queued contact job fails at send time. |
| Optional | `REDIS_*`, `MEMCACHED_HOST`, `AWS_*`, `LOG_*` | Only when those drivers are used. |

## Queue

`POST /contact` and the Livewire `ContactForm` component validate with the same rules (`App\Support\ContactRules`) and **queue** a `ContactMessage` mailable through `App\Services\ContactMessageService` (3 tries, backoff 60s then 300s). The visitor sees "Pesan berhasil dikirim!" as soon as the job is queued, not when the mail is sent.

Production therefore needs one of:

1. **A running worker (recommended):** run `php artisan migrate` so the `jobs` and `failed_jobs` tables exist, then keep `php artisan queue:work --tries=3` running under Supervisor, systemd or the host's worker feature. Run `php artisan queue:restart` on every deploy so workers load the new code.
2. **A deliberate `QUEUE_CONNECTION=sync`:** delivery happens inside the request, so SMTP latency and errors reach the visitor as the generic failure message.

Without a worker, messages pile up in `jobs` and are never sent while visitors are told they were. Monitor `failed_jobs` (`php artisan queue:failed`) and the log entries `Contact message delivery failed.` (worker gave up) and `Contact message could not be queued.` (enqueue or `sync` delivery failed); both are written without personal data.

Queued contact payloads are encrypted (`ShouldBeEncrypted`) and therefore tied to `APP_KEY`; see the [secret rotation runbook](docs/security/secret-rotation-runbook.md) before rotating it.

## Rate limits

| Limiter | Applies to | Limit | Key |
| --- | --- | --- | --- |
| `contact` | `POST /contact` | 5 per minute | client IP |
| `contact` | `POST /contact` | 3 per hour | SHA-256 of the trimmed, lower-cased email |
| `App\Support\ContactRateLimiter` | Livewire `ContactForm` | 5 per minute and 3 per hour | same keys as `contact` |
| `api` | `GET /api/projects`, `GET /api/projects/{slug}` | 60 per minute | client IP |

Trade-offs:

- Invalid submissions count too, which is intended for abuse control.
- Anyone who knows an email address can use up that address's hourly quota and block its owner for up to an hour. This is accepted in exchange for stopping one sender from flooding the inbox through many IPs.
- Throttled `POST /contact` requests redirect to `/#contact` with a generic error and keep the input. `ContactForm` shows the same generic message in place and keeps the input.
- Livewire's update requests (`/livewire-<hash>/update`) do not pass through the `throttle:contact` route middleware, so `ContactForm` enforces the limits itself through `ContactRateLimiter` (same keys, numbers and message). Its counters are kept separately from the `POST /contact` counters.
- **Behind a reverse proxy, load balancer or CDN**, configure trusted proxies (`$middleware->trustProxies(...)` in `bootstrap/app.php`). Otherwise every visitor shares the proxy's IP and the per-IP limits become global (this applies to `ContactForm` too, which reads the IP from the server-side request and never from a header the client controls).
- Limiter counters live in the default cache store. With several app servers, use a shared store (database, Redis, Memcached); a per-node `file`/`array` cache makes the limits per node.

## Security headers

`App\Http\Middleware\SecurityHeaders` is appended to every Laravel response (web, API, `/up`) and sets, unless a route already set them:

- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy` disabling accelerometer, camera, geolocation, gyroscope, magnetometer, microphone, payment and usb
- `X-Frame-Options: SAMEORIGIN`

No `Content-Security-Policy` is sent yet (see [Known gaps](#known-gaps)). Files served directly by the web server from `public/` (build assets, images) do not pass through Laravel, so they need these headers from the server configuration (`.htaccess`, nginx or the CDN).

## Public routes

| Method | URI | Name |
| --- | --- | --- |
| GET | `/` | |
| GET | `/project/{slug}` | `project.show` |
| POST | `/contact` | `contact.store` |
| POST | `/lang/{locale}` (`id` or `en`) | `lang.switch` |
| GET | `/api/projects` | |
| GET | `/api/projects/{slug}` | |

The former `POST /api/contact` endpoint was removed. `tests/Feature/RouteTest.php` pins this surface and the seeded project slugs.

Livewire registers its own endpoints under `/livewire-<hash>/` (`update`, `upload-file`, `preview-file` and its JS/CSS assets). Only `ContactForm` uses Livewire and nothing uploads files. The file endpoints reject requests without a valid signature, and the update endpoint requires a CSRF token and the `X-Livewire` header.

## Sensitive-file guard

Before committing, run from the repository root:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/check-sensitive-files.ps1
```

It checks Git-tracked **path names only** (never file contents) and exits non-zero if `.env` or any `.env.*` variant, SSH private keys (`id_rsa`, `id_dsa`, `id_ecdsa`, `id_ed25519`), or key and certificate stores (`*.pem`, `*.key`, `*.p12`, `*.pfx`, `*.pkpass`, `*.jks`, `*.keystore`) are tracked, or if `.env` is not ignored. `.env.example` and `*.pub` public keys are allowed.

## Deployment checklist

1. Set all required [environment variables](#environment-variables) in the host's secret store, including `APP_KEY`, the mail settings and `CONTACT_RECIPIENT_EMAIL`; keep `APP_DEBUG` false.
2. `composer install --no-dev --optimize-autoloader`
3. `npm ci && npm run build`. `public/build` is Git-ignored (not committed), so build it on the server or in CI; if you build elsewhere, ship the generated `public/build` directory with the release.
4. `php artisan migrate --force`
5. `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
6. Start or keep a queue worker running (`php artisan queue:work --tries=3`) and run `php artisan queue:restart`, or deliberately use `QUEUE_CONNECTION=sync`.
7. Configure trusted proxies if the app runs behind a proxy, load balancer or CDN.
8. Use a shared cache store when running more than one app server.
9. Add `nosniff` and framing headers for static files in the web server or CDN configuration.
10. Smoke-test `/`, a project page and `/up`. No page shows a contact form yet (see [Known gaps](#known-gaps)), so test delivery with a `POST /contact` (it needs a session and a CSRF token) or by temporarily rendering `<livewire:contact-form />`; confirm the message arrives and nothing lands in `failed_jobs`.

## Known gaps

- **The contact form is not placed on any page.** The `#contact` section only offers mailto/WhatsApp links. A Livewire `ContactForm` component with a minimal, unstyled view (`app/Livewire/ContactForm.php`, `resources/views/livewire/contact-form.blade.php`) exists and is covered by tests, but its copy, translations and styling are undecided. The `POST /contact` controller flashes `success` and the errors `contact`/field errors, and no view displays them; a plain HTML form would have to show `session('success')`, `$errors->first('contact')` and the field errors.
- **No Content-Security-Policy yet.** Inventory for a future policy: scripts from `'self'` (the Vite bundle and Livewire's bundled Alpine at `/livewire-<hash>/livewire.min.js`) plus one inline theme script in `layout/welcome.blade.php` (needs a nonce or hash); Alpine's standard build needs `'unsafe-eval'` unless Livewire's CSP-safe build (`livewire.csp.min.js`) is used; styles from `'self'`, `https://fonts.googleapis.com` and inline `style=""` attributes; fonts from `'self'` and `https://fonts.gstatic.com`; images from `'self'` and `data:`; `connect-src 'self'` (language switch and Livewire updates); `form-action 'self'`; plus `base-uri 'self'`, `object-src 'none'`, `frame-ancestors 'self'`. Local dev additionally needs the Vite dev-server origin and `ws:`.
- **No `prefers-reduced-motion` support.** Lenis smooth scrolling, the scroll-reveal effects and the GSAP detail-page animations always run.
- **Static files** in `public/` do not get the security headers unless the web server adds them.
- **Old build output is tracked and publicly served:** `public/build.pre-alpine-backup/` holds an outdated pre-Alpine JavaScript/CSS build that the pages no longer load but that is still reachable under `/build.pre-alpine-backup/`. Remove it in a follow-up change.

## Secret rotation

If a credential or `APP_KEY` may have been exposed, follow [docs/security/secret-rotation-runbook.md](docs/security/secret-rotation-runbook.md). It separates provider credential rotation, `APP_KEY` rotation (including draining encrypted queued jobs) and Git history cleanup, all of which need explicit approval.

## Security review

[docs/owasp-top-10-mapping.md](docs/owasp-top-10-mapping.md) maps the project's findings to the OWASP Top 10:2025 and records the current status of each (dependency audits, CSRF and endpoint checks). Re-run `composer audit` and `npm audit` whenever dependencies change.

## Framework

Built on [Laravel](https://laravel.com), a web application framework with expressive, elegant syntax. See the [Laravel documentation](https://laravel.com/docs) and [Laravel Learn](https://laravel.com/learn).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
