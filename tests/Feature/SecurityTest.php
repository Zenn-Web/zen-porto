<?php

use Illuminate\Support\Facades\Mail;

test('contact form prevents xss via script injection', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => '<script>alert("xss")</script>',
        'last_name' => '<img src=x onerror=alert(1)>',
        'email' => 'test@example.com',
        'message' => '<script>document.cookie</script>',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
});

test('home page escapes html in output', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->not->toContain('<?php');
});

test('application does not expose sensitive environment data', function () {
    $response = $this->get('/');
    $content = $response->getContent();

    expect($content)->not->toContain('APP_KEY');
    expect($content)->not->toContain('DB_PASSWORD');
    expect($content)->not->toContain('MAIL_PASSWORD');
});

test('contact form validates oversized input to prevent dos', function () {
    $response = $this->post(route('contact.store'), [
        'first_name' => str_repeat('A', 5000),
        'last_name' => str_repeat('B', 5000),
        'email' => 'test@example.com',
        'message' => str_repeat('C', 100000),
    ]);

    // first_name and last_name have max:255 validation, message has max:5000
    $response->assertSessionHasErrors(['first_name', 'last_name', 'message']);
});

test('contact form rejects duplicate submissions gracefully', function () {
    Mail::fake();

    $response1 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Test message.',
    ]);
    $response1->assertRedirect();

    $response2 = $this->post(route('contact.store'), [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Test message.',
    ]);
    $response2->assertRedirect();
});

test('application does not leak route information via unexpected methods', function () {
    $response = $this->put('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();

    $response = $this->patch('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();

    $response = $this->delete('/contact');
    expect(in_array($response->getStatusCode(), [405, 404]))->toBeTrue();
});

function maliciousProject(): App\Models\Project
{
    return App\Models\Project::create([
        'title' => '<script>alert(1)</script>',
        'title_en' => "<script>alert('en')</script>",
        'slug' => 'xss-probe',
        'category' => '<img src=x onerror=alert(1)>',
        'category_en' => '"><svg onload=alert(2)>',
        'description' => '<b onmouseover=alert(3)>desc</b>',
        'description_en' => '<b onmouseover=alert(33)>desc en</b>',
        'flow_description' => "Step one <iframe src=javascript:alert(4)>\nStep two",
        'flow_description_en' => "Step EN <img src=x onerror=alert(44)>\nStep EN two",
        'year' => '2026',
        'tech_stack' => ['Laravel'],
    ]);
}

test('home page escapes untrusted project fields in text and data attributes', function (string $locale) {
    maliciousProject();

    $content = $this->withSession(['locale' => $locale])->get('/')->assertOk()->getContent();

    // No executable payload survives anywhere in the document.
    expect($content)
        ->not->toContain('<script>alert(1)</script>')
        ->not->toContain("<script>alert('en')</script>")
        ->not->toContain('<img src=x onerror=alert(1)>')
        ->not->toContain('<svg onload=alert(2)>')
        ->not->toContain('<b onmouseover=');

    // Title, category and description attributes hold escaped text only.
    expect($content)
        ->toContain('data-i18n-id="&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->toContain('data-i18n-en="&lt;script&gt;alert(&#039;en&#039;)&lt;/script&gt;"')
        ->toContain('data-i18n-id="&lt;img src=x onerror=alert(1)&gt;"')
        ->toContain('data-i18n-en="&quot;&gt;&lt;svg onload=alert(2)&gt;"')
        ->toContain('data-i18n-id="&lt;b onmouseover=alert(3)&gt;desc&lt;/b&gt;"')
        ->toContain('data-i18n-en="&lt;b onmouseover=alert(33)&gt;desc en&lt;/b&gt;"');
})->with(['id', 'en']);

test('project detail page escapes untrusted project fields in text and data attributes', function (string $locale) {
    maliciousProject();

    $content = $this->withSession(['locale' => $locale])->get('/project/xss-probe')->assertOk()->getContent();

    expect($content)
        ->not->toContain('<script>alert(1)</script>')
        ->not->toContain("<script>alert('en')</script>")
        ->not->toContain('<iframe src=javascript:alert(4)>')
        ->not->toContain('<img src=x onerror=alert(44)>')
        ->not->toContain('<b onmouseover=');

    expect($content)
        ->toContain('data-i18n-id="&lt;script&gt;alert(1)&lt;/script&gt;"')
        ->toContain('data-i18n-en="&lt;script&gt;alert(&#039;en&#039;)&lt;/script&gt;"')
        ->toContain('data-i18n-id="&lt;b onmouseover=alert(3)&gt;desc&lt;/b&gt;"')
        // Multi-line flow description stays plain text (no <br> markup in attributes).
        ->toContain("data-i18n-id=\"Step one &lt;iframe src=javascript:alert(4)&gt;\nStep two\"")
        ->toContain("data-i18n-en=\"Step EN &lt;img src=x onerror=alert(44)&gt;\nStep EN two\"");
})->with(['id', 'en']);

test('trusted static translation markup is still rendered on the home page', function () {
    $content = $this->withSession(['locale' => 'en'])->get('/')->assertOk()->getContent();

    expect($content)->toContain('<strong>Front-End Developer</strong>');
});

test('language switcher never parses data attributes as html', function () {
    $script = file_get_contents(resource_path('js/alpine-init.js'));

    expect($script)
        ->not->toContain('innerHTML')
        ->not->toContain('outerHTML')
        ->not->toContain('insertAdjacentHTML')
        ->toContain('textContent');
});

test('shipped alpine-init bundle does not write data-i18n values through innerHTML', function () {
    // alpine-init.js is no longer a Vite entry; it ships inside the app.js bundle.
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    $bundlePath = public_path('build/'.$manifest['resources/js/app.js']['file']);

    expect($bundlePath)->toBeFile();
    $bundle = file_get_contents($bundlePath);

    // The bundle must be built from the fixed source (static-markup toggle present).
    expect($bundle)->toContain('data-i18n-lang')->toContain('textContent');

    // No innerHTML assignment inside the data-i18n handler. Alpine's own x-html
    // directive also uses innerHTML, so only an assignment that follows a
    // "data-i18n" reference within the same block (no closing brace) counts.
    expect(preg_match('/data-i18n[^}]*\.innerHTML\s*=/', $bundle))->toBe(0);
});

test('legacy html entities in project text are decoded and shown as plain text', function () {
    App\Models\Project::create([
        'title' => 'Legacy &ndash; Title',
        'title_en' => '&lt;script&gt;alert(5)&lt;/script&gt;',
        'slug' => 'legacy-entities',
        'category' => 'UI/UX &nbsp;&bull;&nbsp; DEVELOPMENT',
        'category_en' => 'UI/UX &nbsp;&bull;&nbsp; DIGITAL BUSINESS',
        'year' => '2026',
    ]);

    $home = $this->withSession(['locale' => 'en'])->get('/')->assertOk()->getContent();
    $show = $this->withSession(['locale' => 'en'])->get('/project/legacy-entities')->assertOk()->getContent();

    foreach ([$home, $show] as $content) {
        expect($content)
            ->not->toContain('&amp;bull;')
            ->not->toContain('&amp;nbsp;')
            ->not->toContain('&amp;ndash;')
            // An entity-encoded payload decodes to text but is escaped again on output.
            ->not->toContain('<script>alert(5)</script>')
            ->toContain('data-i18n-en="&lt;script&gt;alert(5)&lt;/script&gt;"')
            ->toContain("Legacy \u{2013} Title");
    }

    expect($home)->toContain("UI/UX \u{00A0}\u{2022}\u{00A0} DIGITAL BUSINESS");
});

test('seeded project categories render bullets instead of literal entities', function () {
    $this->seed(Database\Seeders\ProjectSeeder::class);

    $content = $this->withSession(['locale' => 'en'])->get('/')->assertOk()->getContent();

    expect($content)
        ->not->toContain('&amp;bull;')
        ->not->toContain('&amp;nbsp;')
        ->not->toContain('&bull;&nbsp;')
        ->toContain("UI/UX \u{2022} DIGITAL BUSINESS");
});

test('project display text leaves valid utf-8 untouched', function () {
    $project = new App\Models\Project(['title' => 'Ö × Õ 中文 😕 ok']);

    expect($project->displayText('title'))->toBe('Ö × Õ 中文 😕 ok');
});

test('project display text remaps legacy windows-1252 bytes only in invalid utf-8', function () {
    $project = new App\Models\Project([
        'title' => "a \x96 b",
        'category' => "x \x95 y \x97 z",
    ]);

    expect($project->displayText('title'))->toBe("a \u{2013} b")
        ->and($project->displayText('category'))->toBe("x \u{2022} y \u{2013} z");
});

test('project display text still decodes legacy html entities', function () {
    $project = new App\Models\Project(['category_en' => 'UI/UX &nbsp;&bull;&nbsp; Ö']);

    expect($project->displayText('category_en'))->toBe("UI/UX \u{00A0}\u{2022}\u{00A0} Ö");
});

test('api contact endpoint no longer exists', function () {
    Mail::fake();

    $response = $this->postJson('/api/contact', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'message' => 'Hello',
    ]);

    expect($response->getStatusCode())->toBeIn([404, 405]);
    expect($response->getContent())->not->toContain('john@example.com');
    Mail::assertNothingQueued();
});

test('contact form rejects line breaks in name fields to prevent header injection', function () {
    Mail::fake();

    $this->post(route('contact.store'), [
        'first_name' => "John\r\nBcc: victim@example.com",
        'last_name' => "Doe\nX-Injected: 1",
        'email' => 'john@example.com',
        'message' => "Multi-line\nmessages stay allowed.",
    ])->assertSessionHasErrors(['first_name', 'last_name']);

    Mail::assertNothingQueued();
});

test('contact message subject never contains control characters', function () {
    $mail = new App\Mail\ContactMessage("John\r\nBcc: victim@example.com", 'Doe', 'john@example.com', 'Hi');

    expect($mail->envelope()->subject)->not->toMatch('/[\r\n]/');
});

test('api project routes are rate limited and keep their response shape', function () {
    App\Models\Project::create(['title' => 'Probe', 'slug' => 'probe', 'year' => '2026']);

    $list = $this->getJson('/api/projects')
        ->assertOk()
        ->assertJsonStructure(['success', 'message', 'data'])
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'List data projek portofolio')
        ->assertHeader('X-RateLimit-Limit', '60');

    $this->getJson('/api/projects/probe')
        ->assertOk()
        ->assertJsonStructure(['success', 'data'])
        ->assertJsonPath('data.slug', 'probe');

    $this->getJson('/api/projects/missing')
        ->assertNotFound()
        ->assertExactJson(['success' => false, 'message' => 'Projek tidak ditemukan']);

    for ($i = 0; $i < 57; $i++) {
        $this->getJson('/api/projects')->assertOk();
    }

    $this->getJson('/api/projects')->assertStatus(429);
});

/**
 * Extract every <script src="..."> tag from rendered HTML.
 *
 * @return array<int, array{tag: string, src: string}>
 */
function renderedScriptTags(string $html): array
{
    preg_match_all('/<script\b[^>]*\bsrc\s*=\s*["\']([^"\']+)["\'][^>]*>/i', $html, $matches, PREG_SET_ORDER);

    return array_map(fn (array $match) => ['tag' => $match[0], 'src' => $match[1]], $matches);
}

function renderedPagesForScriptAudit(): array
{
    App\Models\Project::create(['title' => 'Probe', 'slug' => 'script-probe', 'year' => '2026']);

    return [
        'home' => test()->get('/')->assertOk()->getContent(),
        'project' => test()->get('/project/script-probe')->assertOk()->getContent(),
    ];
}

test('rendered pages do not load unpinned @latest scripts', function () {
    foreach (renderedPagesForScriptAudit() as $page => $html) {
        expect(str_contains($html, '@latest'))->toBeFalse("{$page} page references an unpinned @latest asset");
    }
});

test('rendered pages load bootstrap javascript from a single bundled source', function () {
    foreach (renderedPagesForScriptAudit() as $page => $html) {
        $bootstrapScripts = array_filter(
            renderedScriptTags($html),
            fn (array $script) => preg_match('/bootstrap|popper/i', $script['src']) === 1,
        );

        expect($bootstrapScripts)->toBeEmpty("{$page} page loads Bootstrap/Popper outside the Vite bundle")
            ->and($html)->not->toContain('cdn.jsdelivr.net')
            ->and($html)->not->toContain('bootstrap.min.js');
    }
});

test('any external script on rendered pages is pinned with sri and crossorigin', function () {
    foreach (renderedPagesForScriptAudit() as $page => $html) {
        foreach (renderedScriptTags($html) as $script) {
            if (preg_match('#^(https?:)?//#i', $script['src']) !== 1) {
                continue;
            }

            // Same-origin Vite assets are rendered as absolute APP_URL links.
            if (parse_url($script['src'], PHP_URL_HOST) === parse_url(config('app.url'), PHP_URL_HOST)) {
                continue;
            }

            expect($script['tag'])
                ->toMatch('/\bintegrity\s*=\s*["\']sha(256|384|512)-[A-Za-z0-9+\/=]+["\']/', "{$page}: {$script['src']} has no SRI")
                ->toMatch('/\bcrossorigin\s*=\s*["\']anonymous["\']/', "{$page}: {$script['src']} has no crossorigin");
        }
    }
});

test('navbar collapse is driven by alpine, not by bootstrap javascript', function () {
    $script = file_get_contents(resource_path('js/app.js'));
    $navbar = file_get_contents(resource_path('views/layout/navbar.blade.php'));

    expect($script)
        ->not->toContain('bootstrap/js')
        ->not->toMatch('/\bbootstrap\.Collapse\b/')
        ->not->toMatch('/window\.bootstrap\b/')
        ->and($navbar)
        ->not->toContain('data-bs-')
        ->toContain('x-data="navMenu"');
});

test('the legacy frontend runtime is gone and the target stack is declared', function () {
    $package = json_decode(file_get_contents(base_path('package.json')), true);
    $lock = json_decode(file_get_contents(base_path('package-lock.json')), true);

    $declared = array_merge($package['dependencies'] ?? [], $package['devDependencies'] ?? []);

    // Bootstrap (CSS/JS), Sass and a directly installed Alpine were replaced by Tailwind and the
    // Alpine that Livewire ships. Neither package.json nor the lock file may still carry them.
    foreach (['bootstrap', 'sass', 'alpinejs'] as $legacy) {
        expect($declared)->not->toHaveKey($legacy)
            ->and($lock['packages'])->not->toHaveKey("node_modules/{$legacy}");
    }

    expect($declared)->toHaveKeys(['tailwindcss', '@tailwindcss/vite', 'bootstrap-icons', 'gsap', 'lenis']);
});

function projectWithLinks(?string $liveDemoUrl, ?string $githubUrl): App\Models\Project
{
    return App\Models\Project::create([
        'title' => 'Link probe',
        'slug' => 'link-probe',
        'category' => 'Web',
        'description' => 'Link probe description',
        'year' => '2026',
        'tech_stack' => ['Laravel'],
        'live_demo_url' => $liveDemoUrl,
        'github_url' => $githubUrl,
    ]);
}

dataset('unsafe project urls', [
    'javascript' => ['javascript:alert(1)'],
    'mixed case with leading space' => [' JaVaScRiPt:alert(2)'],
    'data uri' => ['data:text/html,<script>alert(3)</script>'],
]);

test('project links with a non-http scheme are never rendered as href', function (string $url) {
    projectWithLinks($url, $url);

    $pages = [
        $this->get('/')->assertOk()->getContent(),
        $this->get('/project/link-probe')->assertOk()->getContent(),
    ];

    foreach ($pages as $content) {
        expect($content)
            ->not->toMatch('/href\s*=\s*["\']\s*(javascript|data|vbscript):/i')
            ->not->toContain('alert(1)')
            ->not->toContain('alert(2)')
            ->not->toContain('alert(3)');
    }

    // Without a usable repository link the detail page shows the private-repo state.
    expect($pages[1])->toContain('dp-btn-cta--locked')
        ->not->toContain('dp-btn-cta--primary');
})->with('unsafe project urls');

test('project links with an https scheme are still rendered', function () {
    projectWithLinks('https://demo.example.test/app', 'https://github.com/example/repo');

    $this->get('/')->assertOk()
        ->assertSee('href="https://demo.example.test/app"', false);

    $this->get('/project/link-probe')->assertOk()
        ->assertSee('href="https://demo.example.test/app"', false)
        ->assertSee('href="https://github.com/example/repo"', false)
        ->assertDontSee('dp-btn-cta--locked', false);
});

test('project safe url allows only http and https', function () {
    $project = new App\Models\Project;

    $cases = [
        'https://example.test' => 'https://example.test',
        '  HTTP://example.test/path  ' => 'HTTP://example.test/path',
        'javascript:alert(1)' => null,
        ' JaVaScRiPt:alert(2)' => null,
        'data:text/html,x' => null,
        'ftp://example.test' => null,
        '//example.test' => null,
        '/relative/path' => null,
        '' => null,
    ];

    foreach ($cases as $input => $expected) {
        $project->live_demo_url = $input;
        expect($project->safeUrl('live_demo_url'))->toBe($expected);
    }

    $project->github_url = null;
    expect($project->safeUrl('github_url'))->toBeNull();
});
