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

test('skills text and descriptions use the About paragraph size and line height', function () {
    $css = file_get_contents(resource_path('css/components/skills.css'));
    $about = cssBlock(file_get_contents(resource_path('css/about.css')), '.about-bio-col .about-desc');
    preg_match('/font-size:\s*([^;]+);/', $about, $size);
    preg_match('/line-height:\s*([^;]+);/', $about, $height);

    foreach (['.skills-clean-text', '.skills-clean-desc'] as $selector) {
        $block = cssBlock($css, $selector);
        expect($block)->toContain("font-size: {$size[1]}")->and($block)->toContain("line-height: {$height[1]}");
    }
});

test('the skills descriptions are not sized inline, so the stylesheet decides', function () {
    $html = $this->get('/')->getContent();
    preg_match('/<section id="skills".*?<section id="resources"/s', $html, $section);

    expect($section[0] ?? '')->toContain('skills-clean-desc')->not->toMatch('/skills-clean-desc[^>]*style="[^"]*font-size/s');
});
