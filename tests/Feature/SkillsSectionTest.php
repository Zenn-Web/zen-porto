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
