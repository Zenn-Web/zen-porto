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
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.hero_role', [], 'en').'"');
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
        ->and($desktop)->toMatch('/\.skills-title-rule\s*\{[^}]*margin-left:\s*0/s');
});

test('the Bun note is short enough to stay on one line inside its chip', function (string $locale) {
    expect(mb_strlen(__('portfolio.skills_note_runtime', [], $locale)))->toBeLessThanOrEqual(16);
})->with(['id', 'en']);

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

test('a hint tells visitors the folders can be clicked, and each group is a folder with an icon that opens', function () {
    $section = treeSection($this->get('/')->getContent());
    $groups = config('skills.groups');
    $default = array_search(config('skills.default_group'), array_column($groups, 'key'), true);

    expect($section)->toContain('class="skills-hint')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.skills_hint', [], 'en').'"')
        ->and(substr_count($section, 'skill-node-icon'))->toBe(count($groups) + 1); // the groups, plus the parent folder

    preg_match_all('/<i class="bi (bi-folder2(?:-open)?) skill-node-icon"/', $section, $icons);
    foreach (array_slice($icons[1], 1) as $i => $icon) { // the first icon is the parent folder's
        expect($icon)->toBe($i === $default ? 'bi-folder2-open' : 'bi-folder2');
    }
});

test('there is no hub drawing any more: no SVG overlay, no ring, no line-drawing script', function () {
    $section = treeSection($this->get('/')->getContent());
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($section)->not->toContain('<svg')->and($section)->not->toContain('--angle')->and($section)->not->toContain('--n:');
    expect($js)->not->toContain('createElementNS')->and($js)->not->toContain('ResizeObserver')->and($js)->not->toContain('draw()');
    expect(skillsCss())->not->toContain('cos(var(--angle))')->and(skillsCss())->not->toContain('.skills-tree-links');
});

test('the tree script picks a group by click or the up and down arrow keys', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));
    $section = treeSection($this->get('/')->getContent());

    expect($js)->toContain("Alpine.data('skillsTree'")->and($js)->toContain('select(')->and($js)->toContain('focusGroup(');
    expect($section)->toContain('@click="select(')
        ->and($section)->toContain('@keydown.arrow-down.prevent')
        ->and($section)->toContain('@keydown.arrow-up.prevent');
});

test('the open folder is the one that flows: its stub and its list of files light up in the accent colour and move', function () {
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skill-node--group\.is-active::before\s*\{[^}]*animation:\s*skill-flow-x/s')
        ->and($desktop)->toMatch('/\.skill-leaves\.is-active::before\s*\{[^}]*animation:\s*skill-rail/s')
        ->and(skillsCss())->toContain('@keyframes skill-flow-x')
        ->and(skillsCss())->toMatch('/@media \(prefers-reduced-motion: reduce\)\s*\{[^}]*\.skill-leaves\.is-active::before[^}]*animation:\s*none/s');
});

test('below 992px the folder tree stays the centred waterfall and drops the desktop trunk', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);

    expect($m[1] ?? '')->toMatch('/\.skills-tree-grid\s*\{[^}]*flex-direction:\s*column/s')
        ->and($m[1] ?? '')->toMatch('/\.skill-branch::before,\s*\.skill-node--group::before\s*\{[^}]*display:\s*none/s')
        ->and($m[1] ?? '')->toMatch('/\.skill-leaves\.is-active::before\s*\{[^}]*display:\s*none/s');
});

test('from 992px the role is the parent folder on the left and the folders hang off a dashed trunk to its right', function () {
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);
    $desktop = $m[1] ?? '';

    expect($desktop)->toMatch('/\.skills-tree-grid\s*\{[^}]*display:\s*grid[^}]*grid-template-columns:\s*auto minmax\(0, 1fr\)/s')
        ->and($desktop)->toMatch('/\.skill-node--root\s*\{[^}]*flex-direction:\s*column[^}]*align-self:\s*center/s')
        ->and($desktop)->toMatch('/\.skill-node--root::after\s*\{[^}]*border-top:\s*2px dashed/s')
        ->and($desktop)->toMatch('/\.skill-branch:first-child::before\s*\{[^}]*top:\s*1\.05rem/s')
        ->and($desktop)->toMatch('/\.skill-branch\s*\{[^}]*position:\s*relative[^}]*padding-left:\s*2\.25rem/s')
        ->and($desktop)->toMatch('/\.skill-branch::before\s*\{[^}]*border-left:\s*2px dashed/s')
        ->and($desktop)->toMatch('/\.skill-node--group::before\s*\{[^}]*border-top:\s*2px dashed/s')
        ->and($desktop)->toMatch('/\.skill-leaves\s*\{[^}]*flex-direction:\s*column[^}]*padding-left:\s*2\.25rem/s')
        ->and($desktop)->toMatch('/\.skill-leaves \.skill-chip::before\s*\{[^}]*border-top:\s*2px dashed/s');
});

test('the description of the section is longer: what the folder holds and what the three states mean', function (string $locale) {
    $text = __('portfolio.skills_subtitle', [], $locale);

    expect(mb_strlen($text))->toBeGreaterThanOrEqual(190)
        ->and($text)->toContain('DevOps');
})->with(['id', 'en']);

test('below 992px the wrapper keeps the waterfall spacing', function () {
    preg_match('/@media \(max-width: 991px\)\s*\{(.*?)\n\}\n/s', skillsCss(), $m);

    expect($m[1] ?? '')->toMatch('/\.skill-branches\s*\{[^}]*flex-direction:\s*column[^}]*gap:\s*1\.1rem/s')
        ->and($m[1] ?? '')->toMatch('/\.skill-node--root::after\s*\{[^}]*display:\s*none/s');
});

test('the parent folder is a button that starts closed and controls the folders', function () {
    $section = treeSection($this->get('/')->getContent());

    expect($section)->toMatch('/<button type="button" id="skill-root"[^>]*aria-controls="skill-branches"[^>]*aria-expanded="false"[^>]*class="skill-node skill-node--root">/s')
        ->and($section)->toContain('@click="toggle()"')
        ->and($section)->toContain('<div class="skill-branches" id="skill-branches">')
        ->and($section)->toMatch('/<i class="bi bi-folder2 skill-node-icon"[^>]*:class="\{ \'bi-folder2-open\': open, \'bi-folder2\': !open \}"/s')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.hero_role', [], 'en').'"');
});

test('the tree starts closed (no is-open class from the server) and Alpine toggles it', function () {
    $section = treeSection($this->get('/')->getContent());
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($section)->toMatch('/<div class="skills-tree animate-on-scroll"[^>]*:class="\{ \'is-open\': open \}"/s')
        ->and($section)->not->toContain('skills-tree is-open');
    expect($js)->toContain('open: false')->and($js)->toContain('toggle()');
});

test('while closed a note says what is inside, with the real counts, in both languages', function (string $locale) {
    $groups = count(config('skills.groups'));
    $items = collect(config('skills.groups'))->sum(fn ($g) => count($g['items']));
    $section = treeSection($this->withSession(['locale' => $locale])->get('/')->getContent());
    $text = __('portfolio.skills_closed', ['groups' => $groups, 'items' => $items], $locale);

    expect($text)->toContain((string) $groups)->and($text)->toContain((string) $items);
    expect($section)->toContain('class="skill-closed-note"')
        ->and($section)->toContain('data-i18n-'.$locale.'="'.e($text).'"');
})->with(['id', 'en']);

test('each folder knows its order so the folders can appear one after another', function () {
    $section = treeSection($this->get('/')->getContent());

    foreach (array_keys(config('skills.groups')) as $i) {
        expect($section)->toContain('style="--b: '.$i.'"');
    }
});

test('without JavaScript everything is open and the closed note is hidden', function () {
    $section = treeSection($this->get('/')->getContent());

    expect($section)->toMatch('/<noscript><style>[^<]*\.skill-branches\s*\{\s*display:\s*block !important/s')
        ->and($section)->toMatch('/<noscript><style>[^<]*\.skill-closed-note\s*\{\s*display:\s*none !important/s');
});

test('closed hides the folders, open shows them one after another, and the note fills the gap while closed', function () {
    $css = skillsCss();

    expect($css)->toMatch('/\.skills-tree:not\(\.is-open\) \.skill-branches\s*\{[^}]*display:\s*none/s')
        ->and($css)->toMatch('/\.skills-tree\.is-open \.skill-branch\s*\{[^}]*animation:\s*skill-branch-in[^}]*animation-delay:\s*calc\(var\(--b, 0\) \* 60ms\)/s')
        ->and($css)->toContain('@keyframes skill-branch-in')
        ->and($css)->toMatch('/\.skills-tree:not\(\.is-open\) \.skill-closed-note\s*\{[^}]*display:\s*flex/s')
        ->and($css)->toMatch('/\.skills-tree:not\(\.is-open\) \.skill-node--root\s*\{[^}]*animation:\s*skill-root-pulse/s')
        ->and($css)->toMatch('/\.skill-node--root:hover\s*\{[^}]*transform:\s*translateY/s');
});

test('the line from the parent folder draws itself when it opens, and nothing animates for reduced motion', function () {
    $css = skillsCss();
    preg_match('/@media \(min-width: 992px\)\s*\{(.*?)\n\}\n/s', $css, $m);

    expect($m[1] ?? '')->toMatch('/\.skill-node--root::after\s*\{[^}]*transform:\s*scaleX\(0\)/s')
        ->and($m[1] ?? '')->toMatch('/\.skills-tree\.is-open \.skill-node--root::after\s*\{[^}]*transform:\s*scaleX\(1\)/s')
        ->and($css)->toMatch('/@media \(prefers-reduced-motion: reduce\)\s*\{[^}]*\.skills-tree\.is-open \.skill-branch[^}]*animation:\s*none/s');
});

test('the hint now says to open the folder first', function (string $locale) {
    $hint = __('portfolio.skills_hint', [], $locale);

    expect(mb_strtolower($hint))->toContain($locale === 'en' ? 'open' : 'buka');
})->with(['id', 'en']);
