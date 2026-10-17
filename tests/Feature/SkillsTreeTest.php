<?php

// The Skills section is an interactive tree (root -> groups -> technologies) with moving dashed connectors.

function treeSection(string $html): string
{
    preg_match('/<section id="skills".*?<section id="resources"/s', $html, $m);

    return $m[0] ?? '';
}

function skillsCss(): string
{
    return file_get_contents(resource_path('css/components/skills.css'));
}

function treeContrast(string $a, string $b): float
{
    $l = function (string $hex): float {
        [$r, $g, $bl] = array_map(fn ($c) => hexdec($c) / 255, str_split(ltrim($hex, '#'), 2));
        $f = fn ($c) => $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $f($r) + 0.7152 * $f($g) + 0.0722 * $f($bl);
    };

    return (max($l($a), $l($b)) + 0.05) / (min($l($a), $l($b)) + 0.05);
}

test('the skills are drawn as a tree with the role as its root', function () {
    $section = treeSection($this->get('/')->assertOk()->getContent());

    expect($section)->toContain('x-data="skillsTree(')
        ->and($section)->toContain('class="skill-node skill-node--root"')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.hero_role', [], 'en').'"')
        ->and($section)->toContain('skills-tree-links');
});

test('every group is a button that opens its own list, only the default group open', function () {
    $section = treeSection($this->get('/')->getContent());
    $groups = config('skills.groups');
    $default = array_search(config('skills.default_group'), array_column($groups, 'key'), true);

    expect(substr_count($section, 'class="skill-node skill-node--group'))->toBe(count($groups));
    foreach ($groups as $group) {
        expect($section)->toContain('id="skill-group-'.$group['key'].'"')
            ->and($section)->toContain('aria-controls="skill-leaves-'.$group['key'].'"')
            ->and($section)->toContain('id="skill-leaves-'.$group['key'].'"');
    }

    preg_match_all('/<button[^>]*skill-node--group[^>]*>/', $section, $buttons);
    foreach ($buttons[0] as $i => $button) {
        expect($button)->toContain($i === $default ? 'aria-expanded="true"' : 'aria-expanded="false"');
    }
    expect(substr_count($section, 'skill-leaves is-active'))->toBe(1)
        ->and($section)->toContain('x-data="skillsTree('.$default.')"');
});

test('the group that best shows the role (frameworks and runtime) is open first', function () {
    expect(config('skills.default_group'))->toBe('frameworks');

    $section = treeSection($this->get('/')->getContent());
    preg_match('/<ul class="skill-leaves is-active" id="skill-leaves-([a-z]+)"/', $section, $open);

    expect($open[1] ?? '')->toBe('frameworks');
});

test('a hint tells visitors the groups can be clicked, and each group node carries a chevron', function () {
    $section = treeSection($this->get('/')->getContent());

    expect($section)->toContain('class="skills-hint')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.skills_hint', [], 'en').'"')
        ->and($section)->toContain('data-i18n-id="'.__('portfolio.skills_hint', [], 'id').'"')
        ->and(substr_count($section, 'skill-node-chevron'))->toBe(count(config('skills.groups')));
});

test('the summary counts the technologies by status, computed from the config', function () {
    $tally = ['using' => 0, 'learning' => 0, 'planned' => 0];
    foreach (config('skills.groups') as $group) {
        foreach ($group['items'] as $item) {
            $tally[$item['status']]++;
        }
    }

    $section = treeSection($this->get('/')->getContent());
    expect($section)->toContain('class="skills-summary')
        ->and($section)->toContain('data-i18n-en="'.e(__('portfolio.skills_summary', $tally, 'en')).'"')
        ->and($section)->toContain('data-i18n-id="'.e(__('portfolio.skills_summary', $tally, 'id')).'"');
    expect(array_sum($tally))->toBe(collect(config('skills.groups'))->sum(fn ($g) => count($g['items'])));
});

test('the new skills strings exist in both languages', function (string $key) {
    $id = __("portfolio.{$key}", [], 'id');
    $en = __("portfolio.{$key}", [], 'en');

    expect($id)->not->toBe("portfolio.{$key}")->and($en)->not->toBe("portfolio.{$key}")->and($id)->not->toBe($en);
})->with(['skills_hint', 'skills_summary']);

test('all technologies are in the HTML, so nothing is lost without JavaScript or for screen readers', function () {
    $section = treeSection($this->get('/')->getContent());
    $total = collect(config('skills.groups'))->sum(fn ($g) => count($g['items']));

    expect(substr_count($section, 'class="skill-chip '))->toBe($total)
        ->and(substr_count($section, '<ul class="skill-leaves'))->toBe(count(config('skills.groups')))
        ->and($section)->toContain('<noscript>');
});

test('a group shows how many technologies it holds', function () {
    $section = treeSection($this->get('/')->getContent());

    foreach (config('skills.groups') as $group) {
        expect($section)->toContain('<span class="skill-node-count" aria-hidden="true">'.count($group['items']).'</span>');
    }
});

test('the connectors are dashed and flow on the active path, and stand still for reduced motion', function () {
    $css = skillsCss();

    expect($css)->toMatch('/\.skill-link\s*\{[^}]*stroke-dasharray:/s')
        ->and($css)->toMatch('/\.skill-link\.is-active\s*\{[^}]*animation:\s*skill-flow/s')
        ->and($css)->toContain('@keyframes skill-flow')
        ->and($css)->toMatch('/@media \(prefers-reduced-motion: reduce\)\s*\{[^}]*\.skill-link[^}]*animation:\s*none/s');
});

test('only the open group shows its technologies', function () {
    expect(skillsCss())->toMatch('/\.skill-leaves\s*\{[^}]*display:\s*none/s')
        ->and(skillsCss())->toMatch('/\.skill-leaves\.is-active\s*\{[^}]*display:\s*flex/s');
});

test('the open node keeps readable contrast in both themes', function (string $theme) {
    $css = file_get_contents(resource_path('css/tokens.css'));
    $block = $theme === 'dark' ? substr($css, strpos($css, '[data-theme=dark] {')) : substr($css, 0, strpos($css, '[data-theme=dark] {'));
    preg_match('/--text-primary:\s*(#[0-9a-f]{6})/i', $block, $text);
    preg_match('/--bg-card:\s*(#[0-9a-f]{6})/i', $block, $card);

    // The active node is filled with the text colour and written in the card colour.
    expect(treeContrast($text[1], $card[1]))->toBeGreaterThanOrEqual(4.5);
})->with(['light', 'dark']);

test('the tree is wired up with Alpine, redraws on resize and picks a group by click or arrow keys', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain("Alpine.data('skillsTree'")
        ->and($js)->toContain('ResizeObserver')
        ->and($js)->toContain('select(')
        ->and($js)->toContain('createElementNS');

    $section = treeSection($this->get('/')->getContent());
    expect($section)->toContain('@click="select(')->and($section)->toContain('@keydown.arrow-down.prevent');
});

test('the supporting front-end group is the last branch and Bun keeps its runtime note', function () {
    $section = treeSection($this->get('/')->getContent());

    expect(strrpos($section, 'skill-group-frontend'))->toBeGreaterThan(strrpos($section, 'skill-group-devops'))
        ->and($section)->toContain('skill-chip-note');
});

test('the tree script starts from the group the page opened, not always the first', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain("Alpine.data('skillsTree', (initial = 0) =>")->and($js)->toContain('active: initial');
});

test('the section is two sides: the intro (title, subtitle, legend, summary, hint) first, then the tree', function () {
    $section = treeSection($this->get('/')->getContent());

    expect($section)->toContain('class="skills-layout"')
        ->and($section)->toContain('class="skills-intro"');

    $positions = array_map(fn ($needle) => strpos($section, $needle), ['skills-intro', '<h2', 'skills-subtitle', 'skills-legend', 'skills-summary', 'skills-hint', 'class="skills-tree ']);
    expect($positions)->each->not->toBeFalse();
    expect($positions)->toBe(collect($positions)->sort()->values()->all());
});

test('from 992px the intro sits on the left and the tree on the right, left aligned like the About section', function () {
    $css = skillsCss();
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', $css, $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skills-layout\s*\{[^}]*display:\s*grid[^}]*grid-template-columns:\s*minmax\(0, 4fr\) minmax\(0, 8fr\)/s')
        ->and($desktop)->toMatch('/\.skills-intro\s*\{[^}]*text-align:\s*left/s')
        ->and($desktop)->toMatch('/\.skills-legend\s*\{[^}]*flex-direction:\s*column/s')
        ->and($desktop)->toMatch('/\.skills-title-rule\s*\{[^}]*margin-left:\s*0/s');
});

test('the Bun note is short enough to stay on one line inside its chip', function (string $locale) {
    expect(mb_strlen(__('portfolio.skills_note_runtime', [], $locale)))->toBeLessThanOrEqual(16);
})->with(['id', 'en']);

test('from 992px the tree is a hub: the role in the centre, the groups on a ring, the technologies in a panel under it', function () {
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skills-tree-grid\s*\{[^}]*display:\s*grid[^}]*grid-template-rows:/s')
        ->and($desktop)->toMatch('/\.skill-node--root\s*\{[^}]*grid-area:\s*1 \/ 1[^}]*place-self:\s*center/s')
        ->and($desktop)->toMatch('/\.skill-node--group\s*\{[^}]*grid-area:\s*1 \/ 1[^}]*transform:\s*translate\(\s*calc\(cos\(var\(--angle\)\)/s')
        ->and($desktop)->toMatch('/\.skill-leaves\s*\{[^}]*grid-area:\s*2 \/ 1/s');
});

test('below 992px the tree stays the vertical list with its dashed rail', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);

    expect($m[1] ?? '')->toMatch('/\.skills-tree-links\s*\{[^}]*display:\s*none/s')
        ->and($m[1] ?? '')->toMatch('/\.skills-tree-grid\s*\{[^}]*flex-direction:\s*column/s')
        ->and($m[1] ?? '')->toMatch('/\.skill-leaves-title\s*\{[^}]*display:\s*none/s');
});

test('each group knows its place on the ring (index and count), so the ring follows the config', function () {
    $section = treeSection($this->get('/')->getContent());
    $count = count(config('skills.groups'));

    foreach (array_keys(config('skills.groups')) as $i) {
        expect($section)->toContain("--i: {$i}; --n: {$count}");
    }
    expect(skillsCss())->toMatch('/--angle:\s*calc\(-90deg \+ 360deg \* var\(--i\) \/ var\(--n\)\)/');
});

test('the panel under the hub names the open group, in the accent colour, and is hidden from screen readers (the list already has its label)', function () {
    $section = treeSection($this->get('/')->getContent());

    expect(substr_count($section, 'class="skill-leaves-title"'))->toBe(count(config('skills.groups')))
        ->and($section)->toContain('<li class="skill-leaves-title" aria-hidden="true"')
        ->and(skillsCss())->toMatch('/\.skill-leaves-title\s*\{[^}]*color:\s*var\(--accent-text\)/s');
});

test('the lines run from the centre of the hub to each group, with no lines to the technologies', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain('const centre')
        ->and($js)->not->toContain(".skill-leaves.is-active .skill-chip");
});

test('the ring can be walked with all four arrow keys', function () {
    $section = treeSection($this->get('/')->getContent());

    foreach (['arrow-down', 'arrow-up', 'arrow-left', 'arrow-right'] as $key) {
        expect($section)->toContain("@keydown.{$key}.prevent");
    }
});

test('below 992px the tree is a centred waterfall: one dashed line down the middle, centred nodes, no left rail', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);
    $mobile = $m[1] ?? '';

    expect($mobile)->toMatch('/\.skills-tree-grid::before\s*\{[^}]*left:\s*50%[^}]*translateX\(-50%\)/s')
        ->and($mobile)->toMatch('/\.skills-tree-grid\s*\{[^}]*align-items:\s*center/s')
        ->and($mobile)->not->toMatch('/\.skills-tree-grid\s*\{[^}]*padding-left/s')
        ->and($mobile)->toMatch('/\.skill-node--root\s*\{[^}]*align-self:\s*center/s')
        ->and($mobile)->toMatch('/\.skill-node--group\s*\{[^}]*justify-content:\s*center/s')
        ->and($mobile)->toMatch('/\.skill-node--group::before\s*\{[^}]*display:\s*none/s');
});

test('the open group\'s technologies are centred and sit on the page colour so the line does not run through the chips', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);

    expect($m[1] ?? '')->toMatch('/\.skill-leaves\.is-active\s*\{[^}]*justify-content:\s*center[^}]*background:\s*var\(--bg-primary\)/s');
});

test('the nodes sit above the waterfall line and are spaced far enough apart for it to show', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);

    expect($m[1] ?? '')->toMatch('/\.skill-node--root,\s*\.skill-node--group\s*\{[^}]*position:\s*relative[^}]*z-index:\s*1/s')
        ->and($m[1] ?? '')->toMatch('/\.skills-tree-grid\s*\{[^}]*gap:\s*1\.1rem/s');
});
