# Security Hardening Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Reduce the confirmed and conditional security risks found in the Zen Porto Laravel portfolio without turning it into a CMS or changing its product scope.

**Architecture:** Keep the existing Laravel/Blade application and harden it incrementally. Project content remains read-only portfolio data; no admin panel, authentication flow, or CMS is introduced. Interactive behavior stays client-side where possible, while contact delivery and API behavior receive explicit abuse controls.

**Tech Stack:** Laravel 12, PHP 8.2+, Blade, Eloquent, Pest, Vite, Alpine.js, Bootstrap/Sass during this hardening phase.

## Global Constraints

- Do not expose, print, commit, or copy values from `.env`.
- Do not rotate provider credentials, change `APP_KEY`, rewrite Git history, or force-push without an explicit deployment/remote approval checkpoint.
- Do not introduce CMS, admin dashboard, user authentication, or new public API requirements.
- Preserve existing public routes and project slugs unless a task explicitly adds a compatibility test.
- Use Laravel escaping by default; HTML is allowed only for explicitly trusted static translation strings.
- Every behavior change must have a focused test before broad verification.

---

### Task 1: Establish secret-hygiene guardrails

**Files:**
- Modify: `.gitignore`
- Create: `scripts/check-sensitive-files.ps1`
- Create: `docs/security/secret-rotation-runbook.md`
- Test: command-line checks and Git tracking checks

**Interfaces:**
- Produces a local PowerShell guard that exits non-zero when sensitive filenames are tracked or when `.env` is not ignored.
- Produces a provider-neutral runbook that separates credential rotation, `APP_KEY` rotation, and Git history cleanup.

- [ ] **Step 1: Verify the current baseline without exposing secret values**

Run:

```powershell
git ls-files .env
git check-ignore -v .env
git log --all --format="%h %s" -- .env
```

Expected: `.env` is not listed by `git ls-files`, `.env` is ignored, and historical commits are reported without printing file contents.

- [ ] **Step 2: Add a sensitive-file guard**

The guard must inspect only tracked paths and ignore file contents. It must fail for `.env`, private key extensions, and certificate bundles, while allowing `.env.example`.

- [ ] **Step 3: Document the external rotation sequence**

Document that provider credentials must be revoked/rotated first, deployment values updated second, application health checked third, and Git history cleanup coordinated last.

- [ ] **Step 4: Run the guard and tracking checks**

Run:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/check-sensitive-files.ps1
git ls-files | Select-String -Pattern '(^|/)(\.env|.*\.pem|.*\.key|.*\.p12|.*\.pfx)$'
```

Expected: the guard exits 0 and the tracked-sensitive-file query returns no application secret files.

- [ ] **Step 5: Commit**

```powershell
git add .gitignore scripts/check-sensitive-files.ps1 docs/security/secret-rotation-runbook.md
git commit -m "chore: add secret hygiene guardrails"
```

---

### Task 2: Remove unsafe project output and DOM sinks

**Files:**
- Modify: `resources/views/pages/home.blade.php`
- Modify: `resources/views/pages/project/show.blade.php`
- Modify: `resources/js/alpine-init.js`
- Test: `tests/Feature/SecurityTest.php`

**Interfaces:**
- Project text fields render as escaped text.
- Language switching updates text nodes through `textContent` and does not interpret database content as HTML.

- [ ] **Step 1: Add regression tests for untrusted project data**

Create a project with a title such as `<script>alert(1)</script>` and assert the response contains the escaped text rather than an executable tag. Add coverage for category and description attributes as well.

- [ ] **Step 2: Run the focused tests to establish the failure**

```powershell
php artisan test tests/Feature/SecurityTest.php
```

Expected: the new tests fail against raw `{!! !!}` project rendering.

- [ ] **Step 3: Replace raw database output with escaped Blade output**

Use `{{ }}` for project title, category, description, and attributes. Keep `{!! !!}` only for known static translation strings that intentionally contain `<strong>`, `<i>`, or encoded presentation markup.

- [ ] **Step 4: Replace `innerHTML` with a safe text update**

Change the language switcher so ordinary translated values are assigned with `textContent`. If an existing static translation requires markup, handle that as a separate allowlisted static key instead of accepting arbitrary HTML from `data-*` attributes.

- [ ] **Step 5: Run focused tests again**

```powershell
php artisan test tests/Feature/SecurityTest.php
```

Expected: all security tests, including the new project-output tests, pass.

- [ ] **Step 6: Commit**

```powershell
git add resources/views/pages/home.blade.php resources/views/pages/project/show.blade.php resources/js/alpine-init.js tests/Feature/SecurityTest.php
git commit -m "fix: escape project content and remove unsafe dom sink"
```

---

### Task 3: Harden contact delivery and public API abuse controls

**Files:**
- Modify: `app/Http/Controllers/ContactController.php`
- Modify: `routes/api.php`
- Modify: `routes/web.php`
- Modify: `routes/console.php` only if a queue/maintenance command is required
- Create: `app/Http/Requests/ContactRequest.php`
- Create: `app/Mail/ContactMessage.php` if a queued mailable is used
- Create: `app/Jobs/SendContactMessage.php` if delivery is queued
- Test: `tests/Feature/ContactFormTest.php`
- Test: `tests/Feature/SecurityTest.php`

**Interfaces:**
- `ContactRequest` validates `first_name`, `last_name`, `email`, and `message` with an explicit message size limit.
- Web contact delivery returns the existing redirect/session UX.
- API contact either is removed as unused or returns a documented, rate-limited response that uses the same validation and delivery path.

- [ ] **Step 1: Add failing tests for message length and rate limits**

Test that a message over the chosen limit is rejected and that repeated requests eventually return or redirect with a rate-limit response. Test that invalid input never queues or sends mail.

- [ ] **Step 2: Run focused tests to establish the failure**

```powershell
php artisan test tests/Feature/ContactFormTest.php tests/Feature/SecurityTest.php
```

Expected: the new max-length and rate-limit tests fail before implementation.

- [ ] **Step 3: Centralize validation in a Form Request**

Use explicit rules equivalent to:

```php
'first_name' => ['required', 'string', 'max:255'],
'last_name' => ['required', 'string', 'max:255'],
'email' => ['required', 'email:rfc', 'max:255'],
'message' => ['required', 'string', 'max:5000'],
```

- [ ] **Step 4: Add a named rate limiter**

Define an endpoint limiter keyed primarily by IP and secondarily by normalized email when available. Return a generic throttled response without exposing implementation details.

- [ ] **Step 5: Queue email delivery**

Move the SMTP operation out of the web request. Configure bounded retries and failure logging. Do not report “sent” until the application has successfully accepted the job, and preserve the existing user-facing message semantics.

- [ ] **Step 6: Remove or unify the API contact endpoint**

Because the current API endpoint only echoes validated personal data and does not send it, remove it if no consumer exists. If it must remain, route it through the same request object, limiter, queue, and response contract.

- [ ] **Step 7: Run focused tests**

```powershell
php artisan test tests/Feature/ContactFormTest.php tests/Feature/SecurityTest.php
```

Expected: validation, mail, queue, duplicate-submission, and rate-limit tests pass.

- [ ] **Step 8: Commit**

```powershell
git add app/Http app/Mail app/Jobs routes tests/Feature/ContactFormTest.php tests/Feature/SecurityTest.php
git commit -m "fix: rate limit and queue contact delivery"
```

---

### Task 4: Reduce third-party script and configuration risk

**Files:**
- Modify: `resources/views/layout/welcome.blade.php`
- Modify: `package.json`
- Modify: `package-lock.json`
- Modify: `vite.config.js`
- Modify: `config/session.php` only when the production setting is confirmed
- Modify: `config/logging.php` only when security event logging is designed
- Test: `tests/Feature/SecurityTest.php`

**Interfaces:**
- No third-party script uses an unpinned `@latest` URL.
- Bootstrap is loaded from one consistent source/version.
- Security headers are generated by the application only when compatible with the existing inline scripts and external assets.

- [ ] **Step 1: Add a test or static assertion for unpinned scripts**

Assert that rendered layout output does not contain `@latest` and does not load duplicate Bootstrap JavaScript sources.

- [ ] **Step 2: Pin or bundle external scripts**

Prefer bundling dependencies through npm/Vite. If a CDN remains, pin its exact version and add SRI plus `crossorigin="anonymous"`.

- [ ] **Step 3: Align Bootstrap and Popper versions**

Use the package lock version consistently, or remove Bootstrap JavaScript entirely if the migrated UI no longer requires it.

- [ ] **Step 4: Add compatible security headers**

Start with `X-Content-Type-Options`, `Referrer-Policy`, and `Permissions-Policy`. Add CSP after inventorying the existing inline scripts, fonts, and CDN sources; do not deploy a CSP that silently breaks the portfolio.

- [ ] **Step 5: Run focused security tests and build**

```powershell
php artisan test tests/Feature/SecurityTest.php
npm run build
```

Expected: security assertions pass and Vite exits 0 without introducing new runtime errors.

- [ ] **Step 6: Commit**

```powershell
git add resources/views/layout/welcome.blade.php package.json package-lock.json vite.config.js config tests/Feature/SecurityTest.php
git commit -m "chore: pin frontend dependencies and harden headers"
```

---

### Task 5: Add verification and production-readiness checks

**Files:**
- Modify: `tests/Feature/RouteTest.php`
- Modify: `tests/Feature/HomePageTest.php`
- Modify: `tests/Feature/PerformanceTest.php` only for deterministic assertions
- Create: `tests/Feature/SecurityHeadersTest.php` if headers are implemented
- Modify: `README.md`
- Modify: `composer.json` only if a CI/secret-check script is added

**Interfaces:**
- Tests cover route compatibility, escaped project output, contact validation/abuse controls, security headers, and production build behavior.
- README documents local setup, required environment variables by name, queue requirements, and deployment checks without containing secret values.

- [ ] **Step 1: Run the full PHP test suite**

```powershell
php artisan test
```

Expected: all tests pass with no generated sensitive files.

- [ ] **Step 2: Run formatter verification**

```powershell
vendor/bin/pint --test
```

Expected: no application style failures. Do not automatically rewrite unrelated vendor files.

- [ ] **Step 3: Run the frontend production build**

```powershell
npm run build
```

Expected: exit code 0. Treat new warnings as review items, not as proof of security.

- [ ] **Step 4: Run the sensitive-file guard and inspect Git status**

```powershell
powershell -ExecutionPolicy Bypass -File scripts/check-sensitive-files.ps1
git status --short
git diff --check
```

Expected: no tracked secrets, no whitespace errors, and only intentional files changed.

- [ ] **Step 5: Commit documentation and final verification**

```powershell
git add README.md tests composer.json
git commit -m "test: document security hardening verification"
```

---

## External approval checkpoints

The following actions are deliberately excluded from automatic implementation:

1. Rotating provider credentials.
2. Generating and deploying a new `APP_KEY`.
3. Rewriting Git history containing `.env`.
4. Force-pushing rewritten history to the GitHub remote.

These require a deployment window, provider access, collaborator coordination, and a rollback plan.
