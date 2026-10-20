<?php

// What stays true of the Skills section whatever its layout (the tree itself is covered by SkillsTreeTest).

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

    return implode("\n", $bodies);
}

test('the heading, subtitle and legend stay centred above the tree', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));

    expect(cssBlock($css, '.skills-subtitle'))->toContain('text-align: center')
        ->and(cssBlock($css, '.skills-legend'))->toContain('justify-content: center');
});

test('technology chips show their status by shape and stay readable', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));

    expect(cssBlock($css, '.skill-chip'))->toContain('border-radius: 999px')->and(cssBlock($css, '.skill-chip'))->toContain('color: var(--text-primary)');
    expect(cssBlock($css, '.skill-chip--using .skill-dot'))->toContain('background: var(--accent-primary)');
    expect(cssBlock($css, '.skill-chip--learning .skill-dot'))->toContain('border: 2px solid var(--accent-primary)');
    expect(cssBlock($css, '.skill-chip--planned .skill-dot'))->toContain('dashed');
});

test('the skills container is wide enough for the three tree columns', function () {
    $section = $this->get('/')->getContent();
    preg_match('/<section id="skills".*?<section id="resources"/s', $section, $m);

    expect($m[0] ?? '')->toContain('max-width: 1120px');
});

test('the old stacked-list classes are gone from the page', function () {
    $section = $this->get('/')->getContent();
    preg_match('/<section id="skills".*?<section id="resources"/s', $section, $m);

    foreach (['skills-clean-list', 'skills-clean-block', 'skills-clean-label'] as $removed) {
        expect($m[0] ?? '')->not->toContain($removed);
    }
});

test('on wide screens the legend runs in one line and the section keeps compact vertical padding', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', $css, $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skills-legend\s*\{[^}]*flex-wrap:\s*wrap[^}]*justify-content:\s*flex-start/s')
        ->and($desktop)->not->toMatch('/\.skills-legend\s*\{[^}]*flex-direction:\s*column/s')
        ->and($desktop)->toMatch('/#skills\s*\{[^}]*padding-block:\s*3\.5rem !important/s');
});

test('the folder tree is compact: short folders and files, tight gaps', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', $css, $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skill-node--group\s*\{[^}]*min-height:\s*2\.1rem/s')
        ->and($desktop)->toMatch('/\.skill-branch\s*\{[^}]*margin-top:\s*0\.35rem/s')
        ->and($desktop)->toMatch('/\.skill-leaves\s*\{[^}]*gap:\s*0\.25rem/s')
        ->and($desktop)->toMatch('/\.skill-leaves \.skill-chip\s*\{[^}]*font-size:\s*0\.78rem/s');
});
