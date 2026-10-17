<?php

// The Skills section is centred and typeset like the About section above it.

/** All declarations of every rule whose selector list contains exactly this selector. */
function cssBlock(string $css, string $selector): string
{
    $css = preg_replace('#/\*.*?\*/#s', '', $css);
    preg_match_all('/([^{}]+)\{([^}]*)\}/', $css, $rules, PREG_SET_ORDER);

    $bodies = [];
    foreach ($rules as [, $selectors, $body]) {
        if (in_array($selector, array_map('trim', explode(',', $selectors)), true)) {
            $bodies[] = $body;
        }
    }

    return implode("
", $bodies);
}

test('every skills group is centred under the centred heading', function () {
    $block = cssBlock(file_get_contents(resource_path('css/components/skills.css')), '.skills-clean-block');

    expect($block)->toContain('text-align: center');
});

test('skills labels use the About eyebrow style', function () {
    $skills = cssBlock(file_get_contents(resource_path('css/components/skills.css')), '.skills-clean-label');
    $about = cssBlock(file_get_contents(resource_path('css/about.css')), '.about-bio-col .about-eyebrow');

    foreach (['font-size', 'letter-spacing', 'font-weight'] as $property) {
        preg_match("/{$property}:\s*([^;]+);/", $about, $expected);
        expect($skills)->toContain("{$property}: {$expected[1]}");
    }
});

test('the skill groups are spaced apart with a short divider between neighbours, not stacked', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));

    expect(cssBlock($css, '.skills-clean-list'))->toContain('gap: 2rem');

    $divider = cssBlock($css, '.skills-clean-block + .skills-clean-block::before');
    expect($divider)->toContain('width: 40px')->and($divider)->toContain('height: 1px')->and($divider)->toContain('left: 50%');
});

test('the skills groups sit in the spaced list wrapper', function () {
    $html = $this->get('/')->getContent();
    preg_match('/<section id="skills".*?<section id="resources"/s', $html, $section);

    expect($section[0] ?? '')->toContain('skills-clean-list')->not->toContain('gap-6');
});

test('the centred skills text is kept to a comfortable line length', function () {
    $block = cssBlock(file_get_contents(resource_path('css/components/skills.css')), '.skills-clean-block');

    expect($block)->toContain('max-width: 36rem')->and($block)->toContain('margin-inline: auto');
});

test('technology chips show their status by shape and stay readable', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));

    expect(cssBlock($css, '.skill-chip'))->toContain('border-radius: 999px')->and(cssBlock($css, '.skill-chip'))->toContain('color: var(--text-primary)');
    expect(cssBlock($css, '.skill-chip--using .skill-dot'))->toContain('background: var(--accent-primary)');
    expect(cssBlock($css, '.skill-chip--learning .skill-dot'))->toContain('border: 2px solid var(--accent-primary)');
    expect(cssBlock($css, '.skill-chip--planned .skill-dot'))->toContain('dashed');
});

test('from 992px the groups form a two-column grid with the supporting group spanning the bottom, and no divider lines', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', $css, $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skills-clean-list\s*\{[^}]*display:\s*grid[^}]*grid-template-columns:\s*repeat\(2, minmax\(0, 1fr\)\)/s')
        ->and($desktop)->toMatch('/\.skills-clean-block:last-child\s*\{[^}]*grid-column:\s*1 \/ -1/s')
        ->and($desktop)->toMatch('/\.skills-clean-block \+ \.skills-clean-block::before\s*\{[^}]*display:\s*none/s');
});

test('the skills container is wide enough for two columns', function () {
    $section = $this->get('/')->getContent();
    preg_match('/<section id="skills".*?<section id="resources"/s', $section, $m);

    expect($m[0] ?? '')->toContain('max-width: 1040px');
});

test('the list layout lives in the stylesheet, so a utility class cannot override the grid', function () {
    $section = $this->get('/')->getContent();
    preg_match('/<div class="skills-clean-list[^"]*"/', $section, $m);

    expect($m[0] ?? '')->not->toContain('flex');
});
