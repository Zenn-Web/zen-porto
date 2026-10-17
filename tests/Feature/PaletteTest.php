<?php

// Guards the Archive Ochre palette (docs/superpowers/specs/2026-10-04-archive-ochre-palette-design.md).

function paletteSources(): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('css'), FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        if ($file->getExtension() === 'css') {
            $files[$file->getPathname()] = file_get_contents($file->getPathname());
        }
    }

    foreach (['pages/home.blade.php', 'pages/project/show.blade.php', 'layout/welcome.blade.php', 'layout/navbar.blade.php', 'layout/footer.blade.php'] as $view) {
        $path = resource_path("views/{$view}");
        if (is_file($path)) {
            $files[$path] = file_get_contents($path);
        }
    }

    return $files;
}

function paletteTokens(string $block): array
{
    $css = file_get_contents(resource_path('css/tokens.css'));
    $start = $block === 'dark' ? strpos($css, '[data-theme=dark] {') : 0;
    $end = $block === 'dark' ? strlen($css) : strpos($css, '[data-theme=dark] {');
    preg_match_all('/--([a-z-]+):\s*(#[0-9a-f]{6})\s*;/i', substr($css, $start, $end - $start), $m, PREG_SET_ORDER);

    return array_column($m, 2, 1);
}

function contrastRatio(string $a, string $b): float
{
    $luminance = function (string $hex): float {
        [$r, $g, $b] = array_map(fn ($c) => hexdec($c) / 255, str_split(ltrim($hex, '#'), 2));
        $f = fn ($c) => $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $f($r) + 0.7152 * $f($g) + 0.0722 * $f($b);
    };
    [$hi, $lo] = [max($luminance($a), $luminance($b)), min($luminance($a), $luminance($b))];

    return ($hi + 0.05) / ($lo + 0.05);
}

test('no teal or emerald is left in the styles or views, except the green hero status dot', function () {
    $forbidden = '/accent-emerald|text-emerald|text-gradient-emerald|#0fb9a7|#34d399|#0a8f7a|#059669|15,\s*185,\s*167|16,\s*185,\s*129/i';

    foreach (paletteSources() as $path => $source) {
        expect(preg_match($forbidden, $source, $hit))->toBe(0, basename($path).' still contains '.($hit[0] ?? ''));
    }

    // #10b981 is allowed only for the "available" status dot, which is a status signal, not the accent.
    foreach (paletteSources() as $path => $source) {
        if (str_ends_with($path, 'hero.css')) {
            continue;
        }
        expect($source)->not->toContain('#10b981', basename($path).' uses the status green outside the hero');
    }
});

test('no cool slate or bootstrap grey remains in the colour tokens', function () {
    $tokens = file_get_contents(resource_path('css/tokens.css'));

    foreach (['#0f172a', '#1e293b', '#334155', '#94a3b8', '#f8fafc', '#e2e8f0', '#cbd5e1'] as $slate) {
        expect($tokens)->not->toContain($slate);
    }
});

test('both themes define the full ochre token set', function (string $theme) {
    $tokens = paletteTokens($theme);

    foreach (['bg-primary', 'bg-secondary', 'bg-card', 'text-primary', 'text-secondary', 'text-muted', 'border-primary', 'border-light', 'border-dim', 'border-control', 'accent-primary', 'accent-primary-light', 'accent-primary-deep', 'accent-on-primary', 'accent-on-deep'] as $name) {
        expect($tokens)->toHaveKey($name);
    }
})->with(['light', 'dark']);

test('the spec values are used for the page paper and the accent', function () {
    $light = paletteTokens('light');
    $dark = paletteTokens('dark');

    expect([$light['bg-primary'], $light['bg-card'], $light['accent-primary']])->toBe(['#e8e3d5', '#f7f4ec', '#b59a3a'])
        ->and([$dark['bg-primary'], $dark['bg-card'], $dark['accent-primary']])->toBe(['#11110f', '#242219', '#c9af55']);
});

test('text and controls meet WCAG AA contrast on their surfaces', function (string $theme) {
    $t = paletteTokens($theme);
    $accentText = $theme === 'light' ? $t['accent-primary-deep'] : $t['accent-primary'];

    foreach (['bg-primary', 'bg-card'] as $surface) {
        expect(contrastRatio($t['text-primary'], $t[$surface]))->toBeGreaterThanOrEqual(4.5)
            ->and(contrastRatio($t['text-secondary'], $t[$surface]))->toBeGreaterThanOrEqual(4.5)
            ->and(contrastRatio($t['text-muted'], $t[$surface]))->toBeGreaterThanOrEqual(4.5)
            ->and(contrastRatio($accentText, $t[$surface]))->toBeGreaterThanOrEqual(4.5)
            ->and(contrastRatio($t['border-control'], $t[$surface]))->toBeGreaterThanOrEqual(3.0);
    }

    // Ink on the ochre button, and the hover state (text on the deep ochre).
    expect(contrastRatio($t['accent-on-primary'], $t['accent-primary']))->toBeGreaterThanOrEqual(4.5)
        ->and(contrastRatio($t['accent-on-deep'], $t['accent-primary-deep']))->toBeGreaterThanOrEqual(4.5);
})->with(['light', 'dark']);

test('coloured text uses the text accent, never the plain ochre, so light mode stays readable', function () {
    foreach (paletteSources() as $path => $source) {
        expect(preg_match('/(?<![-\w])color:\s*var\(--accent-primary\)/', $source))->toBe(0, basename($path).' sets text colour to the plain accent');
    }
});
