# Portfolio Frontend Stack Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate the portfolio frontend from Bootstrap/Sass-heavy rendering to Laravel Blade-first with Tailwind CSS, Alpine.js, and a narrowly scoped Livewire contact form without changing the portfolio's public behavior or turning it into a CMS.

**Architecture:** Laravel remains the server-rendered application and source of truth for routes, translations, project data, validation, mail, and security. Blade renders static portfolio pages; Alpine handles browser-only UI state; Livewire is used only for the contact form; GSAP/Lenis remain browser-side animation utilities. The migration is incremental so every page remains usable and testable before legacy frontend dependencies are removed.

**Tech Stack:** PHP `^8.2`, Laravel `^12.0`, Blade, Livewire `^4.0`, Tailwind CSS through Vite, Alpine.js supplied by Livewire, GSAP, Lenis, Eloquent, Pest.

## Global Constraints

- This is an existing portfolio at `D:\Web-Project\zen-porto`; work continues on the already-created branch `codex/clean-rebuild-livewire-tailwind`.
- Preserve the public URL contract: `/`, `/project/{project}`, `POST /contact`, `POST /lang/{locale}`, `GET /api/projects`, and `GET /api/projects/{slug}`.
- Preserve the existing project data model, seeded project slugs, translation keys, public assets, contact fields, and SEO-relevant page structure.
- Keep the application Blade-first. Do not convert the homepage, project listing, project detail page, or language system into full Livewire pages.
- Create exactly one application Livewire component for this migration: `ContactForm`.
- Alpine is for browser-local UI state only: mobile navigation, theme toggle, language presentation state, and similar small interactions.
- Livewire includes and initializes Alpine. Do not import or start a second Alpine instance from `resources/js/app.js`, `resources/js/alpine-init.js`, or a CDN.
- `resources/js/alpine-init.js` is a regular module, not a Vite input entry. It is imported once by `resources/js/app.js`; it registers custom Alpine behavior through `alpine:init` and never imports, exposes, or starts Alpine itself. `resources/js/detail-animations.js` remains a page-specific Vite entry until its existing Blade `@vite` usage is intentionally consolidated.
- Keep GSAP and Lenis unless a concrete compatibility failure is demonstrated. Do not redesign the animation language as part of the stack migration.
- Tailwind is the styling system. Bootstrap CSS/JS and Sass are temporary migration dependencies and may be removed only after visual and functional parity is verified.
- `bootstrap-icons` may remain as an icon asset during this migration because it is not the Bootstrap layout/runtime dependency. Replacing the icon set is a separate design task and is not required here.
- Do not add CMS, admin dashboard, authentication, roles, permissions, media management, public write APIs, SPA routing, microservices, or new database tables.
- Do not change content, copy, project records, URLs, or animation timing to make the migration easier.
- Do not manually edit generated files in `public/build/` or `public/css/app.css`; regenerate them with the existing build command.
- Do not upgrade Laravel, PHP, Vite, or unrelated Composer/NPM packages unless the migration cannot build without that upgrade. If an upgrade appears necessary, stop and report it as a separate decision.
- Every task must end with its focused test/build command passing before the next task starts.
- If a proposed change is not required for Tailwind, Alpine, Livewire integration, parity, security, or verification, stop and ask for approval instead of implementing it.

## Current Baseline

The repository already contains:

- Laravel `^12.0`, PHP `^8.2`, Eloquent `Project`, project seed data, language middleware, contact mail, and security middleware.
- Bootstrap and Bootstrap Icons in `package.json`.
- Sass entrypoints under `resources/sass/` and a Vite configuration that still builds Sass bundles.
- Alpine installed directly in `package.json` and initialized by `resources/js/alpine-init.js`.
- GSAP and Lenis already available for browser-side animation and scrolling.
- Blade layout files at `resources/views/layout/` and pages at `resources/views/pages/`.
- A traditional `POST /contact` controller flow with validation, throttling, queued mail, and extensive Pest coverage.
- Read-only project API routes in `routes/api.php`; these are frozen for compatibility and are not expanded.

## Target Boundaries

```text
Browser
├── Server-rendered HTML from Blade
├── Alpine UI state
├── GSAP / Lenis animations
└── Livewire browser runtime for ContactForm

Laravel
├── Routes and controllers
├── Blade layouts/components/pages
├── Livewire ContactForm only
├── Middleware and validation
└── Eloquent project data

Infrastructure
├── Database
├── Session/cache
├── Queue and queue worker
└── External email provider
```

## Known Gaps and Explicit Non-Goals

- `app/Http/Middleware/SecurityHeaders.php` intentionally does not send a Content-Security-Policy during this migration. Do not add a partial CSP as part of the Tailwind/Livewire work.
- If CSP is introduced later, it must be designed for Livewire 4 and Alpine together: evaluate Livewire's `csp_safe`/Alpine CSP mode or a nonce strategy, and account for Vite assets, the inline theme bootstrap, Google Fonts, Livewire directives, and any inline Alpine expressions. A future CSP task must verify production and development asset paths separately.
- The current migration therefore accepts the existing non-CSP security-header posture as a documented follow-up, rather than silently assuming standard Livewire/Alpine evaluation will remain compatible with a future policy.

## File Map Before Editing

| Area | Current files | Migration responsibility |
|---|---|---|
| Dependencies | `composer.json`, `package.json`, lock files | Add Livewire and Tailwind; remove obsolete runtime dependencies only after parity |
| Build | `vite.config.js`, `resources/css/app.css`, `resources/js/app.js` | Make Tailwind CSS and one global browser JS entry the canonical build; keep detail animation as a page-specific entry while it is still pushed by the detail view |
| Alpine | `resources/js/alpine-init.js` | Register custom Alpine behavior as a module imported by `app.js`, without creating a second Alpine runtime or Vite entry |
| Layout | `resources/views/layout/welcome.blade.php` | Include Vite and explicit Livewire assets; preserve head metadata and stacks |
| Shared UI | `resources/views/layout/navbar.blade.php`, `resources/views/layout/footer.blade.php` | Replace Bootstrap layout behavior with Tailwind and Alpine markup |
| Pages | `resources/views/pages/home.blade.php`, `resources/views/pages/project/show.blade.php` | Preserve content and routes while replacing layout classes and form rendering |
| Styling | `resources/sass/**/*.scss` | Migrate visual rules into Tailwind utilities and focused CSS layers |
| Animation | `resources/js/detail-animations.js`, `resources/js/app.js` | Preserve Lenis, GSAP/detail animation, scroll reveal, clock, and anchor behavior |
| Contact | `app/Http/Controllers/ContactController.php`, `app/Http/Requests/ContactRequest.php`, `app/Mail/ContactMessage.php` | Reuse secure mail behavior from both the legacy route and Livewire component |
| Tests | `tests/Feature/*.php` | Preserve current contracts and add Livewire behavior coverage |

---

### Task 1: Freeze the Existing Portfolio Contract

**Files:**
- Read only: `routes/web.php`, `routes/api.php`, `app/Models/Project.php`, `database/seeders/ProjectSeeder.php`
- Read only: `resources/views/layout/welcome.blade.php`, `resources/views/layout/navbar.blade.php`, `resources/views/pages/home.blade.php`, `resources/views/pages/project/show.blade.php`
- Test: existing `tests/Feature/HomePageTest.php`, `tests/Feature/RouteTest.php`, `tests/Feature/ContactFormTest.php`, `tests/Feature/SecurityTest.php`, `tests/Feature/SecurityHeadersTest.php`

**Interfaces:**
- Consumes: current application branch and current route/data behavior.
- Produces: a verified baseline that later tasks must preserve.

- [ ] Run `composer test` and record the result in the task notes.
- [ ] Run `npm run build` and confirm the current asset pipeline completes.
- [ ] Run `php artisan route:list --except-vendor` and capture the public route signatures listed under Global Constraints.
- [ ] Confirm the seeded slugs remain `company-website-eyegil`, `umkm-business-tumbuh`, and `training-platform-amazain`.
- [ ] Do not modify application code in this task. If the baseline fails, diagnose the failure before starting the migration; do not hide it by weakening tests.

**Acceptance criteria:**

- Existing tests and build results are known before migration work begins.
- No public route, model, seed, or content change is introduced by this task.

**Commit:**

```text
chore: record portfolio migration baseline
```

---

### Task 2: Install and Configure the Target Frontend Pipeline

**Files:**
- Modify: `composer.json`, `composer.lock`
- Modify: `package.json`, `package-lock.json`
- Modify: `vite.config.js`
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js`
- Modify: `resources/js/alpine-init.js`

**Interfaces:**
- Consumes: the current Vite entrypoints and existing GSAP/Lenis behavior.
- Produces: a build that contains Tailwind CSS, the existing animation code, and one Livewire-compatible Alpine runtime.

- [ ] Install Livewire 4 with `composer require livewire/livewire:^4.0`; verify the resolved package supports the existing Laravel 12 application before continuing. If Composer cannot resolve that constraint, stop and report the compatibility decision instead of silently falling back to another major version.
- [ ] Install Tailwind's Vite integration with `npm install tailwindcss @tailwindcss/vite`.
- [ ] Configure `vite.config.js` to load `@tailwindcss/vite`. The target global inputs are `resources/css/app.css` and `resources/js/app.js`; keep `resources/js/detail-animations.js` as the existing page-specific input for the project detail view until Task 4/5 deliberately consolidates it. Remove `resources/js/alpine-init.js` from the Vite input list.
- [ ] **Transitional inputs:** keep `resources/sass/app.scss` and `resources/sass/detail-project.scss` in the Vite input list until Task 4 migrates the pages that load them. Removing them earlier breaks `@vite` in `welcome.blade.php` and `pages/project/show.blade.php` (missing manifest entry) and the tests that render those views. They are removed from `vite.config.js` in Task 4 (pages stop loading them) and their source files are deleted in Task 7.
- [ ] Turn `resources/css/app.css` (currently only a comment) into the Tailwind entrypoint. While Bootstrap/Sass is still loaded, import Tailwind **without preflight** so its reset does not fight Bootstrap's: `@import "tailwindcss/theme.css" layer(theme);` and `@import "tailwindcss/utilities.css" layer(utilities);`, followed by a clearly separated application layer for temporary parity styles. Preflight (`@import "tailwindcss/preflight.css" layer(base);`, or switching back to `@import "tailwindcss";`) is enabled in Task 4.
- [ ] Import `./alpine-init.js` from `resources/js/app.js` so custom Alpine registration is bundled once.
- [ ] Remove direct `import Alpine from 'alpinejs'`, `window.Alpine = Alpine`, and `Alpine.start()` from `resources/js/alpine-init.js`; register custom stores through the `alpine:init` event so Livewire owns Alpine initialization.
- [ ] Keep the `alpine:init` listener in the global bundle before Livewire initializes Alpine. With `@vite` in the head and `@livewireScripts` before `</body>`, verify in a browser that the custom `lang` store is registered before any `x-data` expression evaluates and that no second Alpine start occurs. If script timing requires a change, use Livewire 4's documented initialization hook/configuration; do not add a second Alpine runtime.
- [ ] Keep Lenis and existing browser animation imports working from `resources/js/app.js`.
- [ ] Do not delete Bootstrap or Sass packages/source yet. The Sass bundles stay as live Vite inputs (see transitional inputs above) so every page keeps its current styling until Task 4; their final removal belongs to Task 4 (inputs and Blade references) and Task 7 (packages and source files).
- [ ] Run `npm run build` and verify that no duplicate-Alpine initialization code exists with `rg "Alpine\.start|import Alpine|window\.Alpine" resources/js`.

**Acceptance criteria:**

- `composer.json` contains Livewire and `package.json` contains Tailwind's Vite integration.
- `npm run build` passes.
- Alpine is initialized by Livewire exactly once; custom language/theme registration remains available.
- GSAP/Lenis assets still compile.

**Commit:**

```text
build: add livewire and tailwind pipeline
```

---

### Task 3: Make the Blade Layout and Shared Navigation Stack-Aware

**Files:**
- Modify: `resources/views/layout/welcome.blade.php`
- Modify: `resources/views/layout/navbar.blade.php`
- Modify: `resources/views/layout/footer.blade.php`
- Create only if needed: `resources/views/components/icon.blade.php`

**Interfaces:**
- Consumes: Tailwind/Vite assets from Task 2 and Livewire's automatic Alpine runtime.
- Produces: a layout that can render Blade pages and the ContactForm without duplicate scripts or missing CSRF metadata.

- [ ] Keep the existing `csrf-token`, viewport, favicon, manifest, title, font preconnect, `@stack('styles')`, and `@stack('scripts')` behavior.
- [ ] Change the layout's `@vite` to `@vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])`: keep the Sass bundle for the still-Bootstrap pages, add the Tailwind CSS, and drop the separate `alpine-init.js` entry. The Sass entry is removed from the layout in Task 4.
- [ ] Add `@livewireStyles` in the document head and `@livewireScripts` before the closing body tag.
- [ ] Treat the two directives above as the sole Livewire asset boundary: Livewire 4 supplies and starts its bundled Alpine runtime, while the Vite bundle only registers custom behavior. Do not add a CDN Alpine script, `Alpine.start()`, or a second `@vite` Alpine entry.
- [ ] Preserve the existing `@yield('content')`, navbar include, footer include, and page-level script stack.
- [ ] Replace Bootstrap navbar collapse markup and `data-bs-*` attributes with Alpine state, `x-show`, `x-transition`, `x-cloak`, and correct `aria-expanded` behavior.
- [ ] Remove the `bootstrap/js/dist/collapse` import and any Collapse-specific code in `resources/js/app.js` in this same task, so the navbar no longer depends on `data-bs-*` markup that no longer exists. Lenis stop/start for the menu is wired here; Task 5 only verifies and polishes it.
- [ ] Preserve every existing navigation target: `/#home`, `/#about`, `/#skills`, `/#resources`, and `/#contact`.
- [ ] Preserve the existing language and theme controls. The migration may change their markup and classes, but not their visible behavior or storage key `theme`.
- [ ] Keep Bootstrap Icons only as an icon asset if replacing them is not required for layout behavior. Do not introduce a new icon package.
- [ ] Run `composer test` and `npm run build` after the layout changes.

**Acceptance criteria:**

- The layout renders without a duplicate Alpine error.
- Mobile navigation opens/closes without Bootstrap JavaScript.
- Theme and language controls remain keyboard-accessible and preserve their existing behavior.
- `/` and `/project/{project}` still return successful responses.

**Commit:**

```text
refactor: move shared layout behavior to blade and alpine
```

---

### Task 4: Migrate Portfolio Pages from Bootstrap/Sass to Tailwind

**Files:**
- Modify: `resources/views/pages/home.blade.php`
- Modify: `resources/views/pages/project/show.blade.php`
- Modify: `resources/css/app.css`
- Modify: the Sass files currently imported by `resources/sass/app.scss`: `resources/sass/_variables.scss`, `resources/sass/_app_ref.scss`, `resources/sass/_about_redesign.scss`, `resources/sass/detail-project.scss`, and `resources/sass/components/*.scss`
- Test: `tests/Feature/HomePageTest.php`, `tests/Feature/RouteTest.php`, `tests/Feature/LayoutShiftPreventionTest.php`

**Interfaces:**
- Consumes: shared layout from Task 3, existing translation keys, `Project` model fields, and current animation selector contracts.
- Produces: visually equivalent Blade pages using Tailwind classes and focused custom CSS only where utility classes cannot express existing animation/layout behavior.

- [ ] Once a page no longer needs Bootstrap/Sass, drop its Sass `@vite` reference: remove `resources/sass/app.scss` from `welcome.blade.php` and `resources/sass/detail-project.scss` from `pages/project/show.blade.php` (keep `resources/js/detail-animations.js`), then remove both Sass inputs from `vite.config.js` and enable Tailwind preflight in `resources/css/app.css`. Do this only after both pages pass the parity check below.
- [ ] Preserve all semantic section IDs: `home`, `about`, `skills`, `resources`, and `contact`.
- [ ] Preserve all project links, `route('project.show', $project->slug)`, safe external URL handling, escaped project data, translation keys, image paths, and accessibility attributes.
- [ ] Replace Bootstrap layout classes such as `container`, `row`, `col-*`, `d-flex`, `gap-*`, `mx-*`, and `btn-*` with Tailwind utilities or project-specific semantic classes backed by Tailwind layers.
- [ ] Move color variables, typography, spacing, responsive breakpoints, and component states from Sass into Tailwind theme tokens and/or `@layer components` in `resources/css/app.css`.
- [ ] Preserve desktop, tablet, and mobile layout intent; do not redesign the visual identity.
- [ ] Preserve animation hooks used by `resources/js/app.js` and `resources/js/detail-animations.js`, including `.text-reveal`, `.animate-on-scroll`, `.animate-buttons`, `.reveal-ready`, `.project-card`, and the detail-page `data-dp-anim` attributes.
- [ ] Preserve image dimensions, lazy-loading behavior, `alt` text, `rel="noopener"`, and safe URL accessors.
- [ ] Run `composer test`, `npm run build`, and a manual browser check for `/` and one seeded project detail page at mobile and desktop widths.

**Acceptance criteria:**

- Homepage and project detail pages render with the target Tailwind styling pipeline.
- No public route or project data contract changes.
- Existing layout-shift and homepage tests pass.
- No page requires Bootstrap JavaScript to function.

**Commit:**

```text
refactor: migrate portfolio pages to tailwind
```

---

### Task 5: Consolidate Browser Interactions without Expanding the Frontend Scope

**Files:**
- Modify: `resources/js/app.js`
- Modify: `resources/js/alpine-init.js`
- Modify: `resources/js/detail-animations.js` only when a selector changed in Task 4
- Modify: `resources/views/layout/navbar.blade.php`
- Modify: `resources/views/pages/home.blade.php` only for Alpine bindings needed by existing interactions
- Modify: `resources/views/pages/project/show.blade.php` only for existing animation bindings

**Interfaces:**
- Consumes: the Tailwind DOM and Alpine initialization from Tasks 2–4.
- Produces: browser interactions with no Bootstrap runtime dependency and no new SPA state layer.

- [ ] Keep Lenis initialization, navbar-height CSS variable updates, anchor scrolling, live clock, intersection-observer reveal behavior, and contact auto-scroll behavior unless an existing selector is intentionally renamed and updated everywhere.
- [ ] Move mobile menu open/close state to Alpine and ensure opening the menu stops Lenis while closing it restarts Lenis.
- [ ] Keep language switching text-safe: use `textContent` for attribute-based translations and preserve server-rendered markup variants for translations containing markup.
- [ ] Keep the theme storage key `theme` and the `data-theme` attribute unchanged.
- [ ] Respect `prefers-reduced-motion` for newly touched animation code and do not introduce a new animation library.
- [ ] Run `npm run build` and manually verify menu, theme, language, anchor links, smooth scrolling, and detail-page animations.

**Acceptance criteria:**

- No `bootstrap/js` import, `data-bs-*` behavior, or Bootstrap collapse listener remains in the active browser path.
- Alpine, Lenis, and existing detail animations work without console errors.
- No React/Vue/SPA state management is introduced.

**Commit:**

```text
refactor: replace bootstrap interactions with alpine
```

---

### Task 6: Add the Single Livewire ContactForm Island

**Files:**
- Create: `app/Livewire/ContactForm.php`
- Create: `resources/views/livewire/contact-form.blade.php`
- Create: `app/Services/ContactMessageService.php`
- Create: `app/Support/ContactRules.php`
- Create: `app/Support/ContactRateLimiter.php` (or an equivalent single shared support/service class)
- Create: `tests/Feature/Livewire/ContactFormTest.php`
- Modify: `app/Http/Requests/ContactRequest.php`
- Modify: `app/Http/Controllers/ContactController.php`
- Modify: `resources/views/pages/home.blade.php`
- Preserve: `app/Mail/ContactMessage.php`, `app/Providers/AppServiceProvider.php`, `routes/web.php`

**Interfaces:**
- `ContactRules::rules(): array` returns the shared validation rules for `first_name`, `last_name`, `email`, and `message`.
- `ContactRateLimiter` owns the existing `contact-ip:` and normalized `contact-email:` key construction and exposes the same five-per-minute IP and three-per-hour email checks for both the route middleware and Livewire.
- `ContactMessageService::queue(array $validated): void` queues the existing encrypted `ContactMessage`, logs queue failures once using only the exception class, and rethrows so both callers can report the same generic user-facing error.
- `ContactForm` exposes public fields `first_name`, `last_name`, `email`, and `message`, plus a `submit(): void` action.
- The legacy `POST /contact` controller and the Livewire component both consume the same rules and message service.

- [ ] Extract the existing validation rules from `ContactRequest` into `ContactRules::rules(): array` without changing required fields, max lengths, RFC email validation, or control-character rejection.
- [ ] Update `ContactRequest` to return `ContactRules::rules()` and preserve its fixed redirect URL.
- [ ] Extract the existing queue call from `ContactController` into `ContactMessageService::queue(array $validated): void` without changing recipient configuration, reply-to address, subject, encryption, retries, or logging policy. The service must catch queue/enqueue exceptions, log exactly once with `exception => $exception::class`, and rethrow without logging the exception object or its message.
- [ ] Define one user-facing failure message, exactly preserving `Pesan gagal dikirim. Silakan coba lagi nanti.`, in the shared contact service/support contract. `ContactController` and `ContactForm` must consume that same message; neither may expose `$exception->getMessage()` or duplicate payload-bearing logging.
- [ ] Add `ContactRateLimiter` (or an equivalent single shared support/service class) that normalizes email with `mb_strtolower(trim($email))`, constructs the existing `contact-ip:` and SHA-256 `contact-email:` keys, and provides checks/hits usable outside route middleware. Keep the current behavior that the email limit applies only when the email is a non-empty string; never hash an empty value into a shared key.
- [ ] Define the throttle message `Terlalu banyak permintaan. Silakan coba lagi nanti.` in the same shared place as the delivery-error message; `AppServiceProvider` and `ContactForm` both consume it.
- [ ] Update `ContactController` to call the shared message service so the public `POST /contact` compatibility route continues to work. Keep the existing `throttle:contact` middleware response behavior for that route, but make its limiter definition use the same key helper.
- [ ] Implement `ContactForm::submit()` to validate with the shared rules, explicitly call the shared limiter for the Livewire update request, reject when either `tooManyAttempts` check is true. To match the legacy route (whose middleware counts every request, valid or not), `hit` the IP key at the start of `submit()` before validation; `hit` the email key as soon as a non-empty email string is present, also before validation. The `/livewire.../update` request does not pass through `throttle:contact`, so relying on route middleware alone is insufficient.
- [ ] Derive the Livewire IP from Laravel's server-side request (`request()->ip()` or the component request), never from a client field. Verify the application's trusted-proxy configuration in the rate-limit tests so proxied and direct requests use the intended IP identity.
- [ ] On rate-limit rejection, return the same generic throttling state/message without incrementing the other limiter unnecessarily; on queue failure, use the shared generic delivery-error message. Reset fields only after a successful queue call.
- [ ] Render the form with `wire:submit="submit"`, `wire:model` bindings, `wire:loading`, field-level errors, a success message, and accessible labels. Do not expose raw exception messages or personal data.
- [ ] Replace only the current contact form markup in `resources/views/pages/home.blade.php` with `<livewire:contact-form />`; keep the surrounding contact section and its content unchanged.
- [ ] Add tests for successful submission, required fields, invalid email, max lengths, rate limiting, queue failure, encrypted queued mail, and absence of personal data in logs/payloads.
- [ ] Run `php artisan test tests/Feature/Livewire/ContactFormTest.php tests/Feature/ContactFormTest.php`.
- [ ] Include coverage proving Livewire does not inherit `throttle:contact` automatically: the component must enforce both limits itself, use normalized email keys, and match the legacy route's key construction. Include coverage proving the service logs one exception-class-only record and both callers expose the same generic delivery-error message.

**Acceptance criteria:**

- The contact form is the only new Livewire component.
- Both Livewire submission and legacy `POST /contact` use the same validation and queue behavior.
- No new database table is introduced.
- Invalid, throttled, and failed submissions do not report false success.
- Existing contact security tests remain green.

**Commit:**

```text
feat: add scoped livewire contact form
```

---

### Task 7: Remove Obsolete Layout Dependencies after Parity

**Files:**
- Modify: `package.json`, `package-lock.json`
- Modify: `vite.config.js`
- Delete only after `rg` confirms no active references: `resources/sass/**/*.scss`
- Modify: `resources/css/app.css`
- Modify: any Blade file still containing Bootstrap layout classes or `data-bs-*` attributes

**Interfaces:**
- Consumes: completed Tailwind pages, Alpine interactions, and passing Livewire tests from Tasks 4–6.
- Produces: a frontend build that no longer depends on Bootstrap CSS/JS or Sass.

- [ ] Run `rg "bootstrap/scss|bootstrap/js|data-bs-|@import.*bootstrap|resources/sass|\.scss" resources vite.config.js package.json` and resolve every active runtime/build reference.
- [ ] Remove `bootstrap`, `sass`, and direct `alpinejs` only when they are no longer imported. Keep `bootstrap-icons` unless an approved icon replacement is implemented in this same task.
- [ ] Confirm `vite.config.js` has no Sass entrypoints left (removed in Task 4) and delete the Sass source files only after the build no longer references them.
- [ ] Do not delete generated `public/build` files manually; regenerate them with `npm run build`.
- [ ] Run `npm install`, `npm run build`, and `composer test`.

**Acceptance criteria:**

- `npm run build` succeeds without Sass or Bootstrap CSS/JS.
- `rg` finds no active Bootstrap layout/runtime imports or Sass entrypoints.
- `bootstrap-icons` is either intentionally retained as an icon-only asset or removed only with an explicit local replacement; it must not provide layout or behavior.
- All public pages and tests remain functional.

**Commit:**

```text
chore: remove legacy frontend runtime dependencies
```

---

### Task 8: Final Verification and Migration Handoff

**Files:**
- Test: all existing tests under `tests/`
- Verify: `composer.json`, `package.json`, `vite.config.js`, `resources/views/layout/welcome.blade.php`
- Review against: `docs/owasp-top-10-mapping.md` (OWASP Top 10:2025 mapping)
- Optional documentation update only if required: `README.md`

**Interfaces:**
- Consumes: the migrated application from Tasks 1–7.
- Produces: evidence that the stack migration is complete without route, security, or scope regression.

- [ ] Run `composer test`.
- [ ] Run `npm run build`.
- [ ] Run `git diff --check`.
- [ ] Run `php artisan route:list --except-vendor` and compare public routes with the Global Constraints list.
- [ ] Run `rg "livewire|tailwind|alpine|gsap|lenis|bootstrap|sass" composer.json package.json vite.config.js resources app` and verify each result has an intentional role.
- [ ] Confirm the plan's known CSP gap remains explicit: no CSP header was added, and any future CSP work is tracked separately with Livewire 4/Alpine CSP or nonce requirements.
- [ ] **OWASP Top 10:2025 review.** Re-read `docs/owasp-top-10-mapping.md` and check every category against the evidence in the final code, writing down the result (finding, evidence such as a test or command output, or "not applicable"). At minimum:
  - **A01 Broken Access Control:** the endpoints Livewire registers (`update`, `upload-file`, `preview-file`) are understood and nothing beyond `ContactForm` is reachable; the unused upload endpoint is signed/throttled or disabled.
  - **A02 Security Misconfiguration:** no CSP is sent (known gap above); the other security headers still pass `SecurityHeadersTest`; no debug or example configuration was introduced.
  - **A03 / A08 Supply Chain and Integrity:** run `composer audit` and `npm audit`; list the advisories that touch code this application runs (for example `laravel/framework`, and the `email:rfc` validation rule used by `ContactRules`), and record whether each needs action. Do not upgrade Laravel or other packages inside this migration; report it as a separate decision. External scripts keep their pinned/SRI tests green.
  - **A05 Injection:** project data stays escaped, the language switcher still writes `textContent` only, the Livewire view uses escaped output, and header injection through the contact fields is still rejected.
  - **A06 Insecure Design:** the contact rate limits (per IP and per normalized email) hold on both `POST /contact` and the Livewire path, including the spoofed `X-Forwarded-For` case.
  - **A09 Logging:** contact failures are logged without personal data; note that 429/validation events are not monitored (operational follow-up, not part of this migration).
  - **A10 Exceptional Conditions:** a queue failure is never reported as success on either contact path.
  - Prove the CSRF protection of the Livewire update endpoint with a real check (Laravel skips CSRF verification while running tests, so the Pest suite does not show it): a request without a valid token must be rejected in a running application.
  - Update `docs/owasp-top-10-mapping.md` to the current state (it still describes the pre-migration audit of 2026-10-01) as a separate documentation commit.
- [ ] Manually verify at minimum: homepage, project detail, mobile navigation, theme toggle, language toggle, anchor navigation, and external project links. The contact form is not placed on any page (the Livewire component and its minimal view exist and are covered by automated tests: success, validation error, throttling, queue failure), and reduced-motion behavior is a known gap (the site does not implement it); record both as follow-ups instead of verifying them manually.
- [ ] Confirm no CMS/admin/auth/API expansion, new table, unrelated package upgrade, or content rewrite entered the diff.
- [ ] Report any remaining visual mismatch as a separate follow-up issue; do not expand the migration task to redesign the portfolio.

**Acceptance criteria:**

- Full Pest suite passes.
- Production asset build passes.
- Public route and security contracts pass.
- Every OWASP Top 10:2025 category has a recorded result (finding, evidence, or not applicable), and dependency advisories are reported as a separate decision rather than fixed inside this migration.
- The final diff is limited to the files and responsibilities described in this plan.

**Commit:**

```text
test: verify portfolio stack migration
```

## Explicit Stop Conditions for Agents

The agent must stop and request approval instead of proceeding when any of these occur:

- A task would require changing a public URL, route method, project slug, translation key, or database schema.
- A task would add an admin panel, CMS, login, role system, public write API, or authentication flow.
- A task would require converting a full page to Livewire or adding a second Livewire component.
- A task would require replacing GSAP/Lenis, redesigning animations, or changing portfolio content.
- A task would require upgrading Laravel, PHP, Vite, or unrelated dependencies.
- Existing baseline behavior cannot be preserved without changing the product requirement.
- A test or build failure is unrelated to the current task and cannot be explained from the task's files.

## Reference Documentation

- Livewire installation and Alpine integration: <https://livewire.laravel.com/docs/4.x/installation>
- Tailwind Vite integration: <https://tailwindcss.com/docs/installation/using-vite>
- Project architecture visual reference: `zen-porto.drawio`, page `02-target-architecture`
