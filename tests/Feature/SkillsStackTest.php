<?php

// The Skills section presents a Backend Developer's stack honestly: every technology carries a status
// (Using / Learning / Planned) and nothing is claimed beyond it.

function skillsSection(string $html): string
{
    preg_match('/<section id="skills".*?<section id="resources"/s', $html, $m);

    return $m[0] ?? '';
}

function expectedStack(): array
{
    return [
        'languages' => ['Java', 'PHP', 'JavaScript', 'TypeScript'],
        'frameworks' => ['Spring Boot', 'Laravel', 'NestJS', 'Express.js', 'Bun'],
        'api' => ['REST API', 'Bruno', 'PHPUnit', 'Maven Surefire', 'Jest', 'Playwright'],
        'data' => ['PostgreSQL', 'MySQL', 'SQLite', 'Eloquent ORM', 'DBeaver', 'Redis'],
        'tools' => ['Git & GitHub', 'VS Code', 'IntelliJ IDEA', 'Maven', 'Composer', 'Vite', 'Laragon', 'Laravel Herd', 'Jira'],
        'devops' => ['Docker', 'GitHub Actions', 'Linux', 'Windows', 'Hostinger', 'Laravel Cloud'],
        'frontend' => ['HTML', 'CSS', 'React', 'Tailwind CSS', 'Bootstrap'],
    ];
}

function stackGroup(string $key): array
{
    foreach (config('skills.groups') as $group) {
        if ($group['key'] === $key) {
            return $group;
        }
    }

    return [];
}

function stackStatus(string $name): ?string
{
    foreach (config('skills.groups') as $group) {
        foreach ($group['items'] as $item) {
            if ($item['name'] === $name) {
                return $item['status'];
            }
        }
    }

    return null;
}

test('every requested technology is listed in its group, in order', function () {
    foreach (expectedStack() as $key => $names) {
        expect(array_column(stackGroup($key)['items'] ?? [], 'name'))->toBe($names, "group {$key}");
    }
});

test('the groups appear in the requested order with the front-end group last', function () {
    expect(array_column(config('skills.groups'), 'key'))->toBe(array_keys(expectedStack()));
});

test('no technology appears in two groups', function () {
    $names = collect(config('skills.groups'))->flatMap(fn ($g) => array_column($g['items'], 'name'));

    expect($names->duplicates()->all())->toBe([]);
});

test('Go, Canva and Figma are not on the page', function () {
    $section = skillsSection($this->get('/')->getContent());

    foreach (['>Go<', '>Canva<', '>Figma<'] as $removed) {
        expect($section)->not->toContain($removed);
    }
    expect($section)->toContain('>Jira<');
});

test('Jira is the project tracking tool in use', function () {
    expect(stackStatus('Jira'))->toBe('using');
});

test('TypeScript is only planned and the backend tools being picked up are marked as learning, not as used', function () {
    expect(stackStatus('TypeScript'))->toBe('planned');

    foreach (['Spring Boot', 'NestJS', 'Express.js', 'Bun', 'Docker', 'GitHub Actions'] as $name) {
        expect(stackStatus($name))->toBe('learning', $name);
    }
    expect(stackStatus('Laravel'))->toBe('using')->and(stackStatus('PHP'))->toBe('using');
});

test('every technology has a known status', function () {
    foreach (config('skills.groups') as $group) {
        foreach ($group['items'] as $item) {
            expect($item['status'])->toBeIn(['using', 'learning', 'planned']);
        }
    }
});

test('Bun is shown as a runtime inside the frameworks and runtime group, not as a framework', function () {
    expect(stackGroup('frameworks')['key'])->toBe('frameworks')
        ->and(__('portfolio.skills_group_frameworks', [], 'en'))->toBe('Backend Frameworks & Runtime');

    $bun = collect(stackGroup('frameworks')['items'])->firstWhere('name', 'Bun');
    expect($bun['note'])->toBe('runtime')
        ->and(__('portfolio.skills_note_runtime', [], 'en'))->toContain('runtime');

    $section = skillsSection($this->get('/')->getContent());
    expect($section)->toContain(e(__('portfolio.skills_note_runtime', [], 'id')));
});

test('each chip says its status in words for screen readers, and a legend explains the three states', function () {
    $section = skillsSection($this->get('/')->getContent());
    $chips = substr_count($section, 'class="skill-chip ');
    $total = collect(config('skills.groups'))->sum(fn ($g) => count($g['items']));

    expect($chips)->toBe($total)
        ->and(substr_count($section, 'class="sr-only"'))->toBe($total)
        ->and($section)->toContain('skills-legend');
    foreach (['using', 'learning', 'planned'] as $status) {
        expect($section)->toContain("skill-chip--{$status}")->and($section)->toContain("skills-legend-item--{$status}");
    }
});

test('every skills string exists in both languages and really differs where it is translated', function (string $key) {
    $id = __("portfolio.{$key}", [], 'id');
    $en = __("portfolio.{$key}", [], 'en');

    expect($id)->not->toBe("portfolio.{$key}")->and($en)->not->toBe("portfolio.{$key}")->and($id)->not->toBe('')->and($en)->not->toBe('');
})->with(fn () => array_merge(
    ['skills_subtitle', 'skills_status_using', 'skills_status_learning', 'skills_status_planned', 'skills_note_runtime'],
    array_map(fn ($key) => "skills_group_{$key}", array_keys(expectedStack())),
));

test('the subtitle presents the profile as a backend developer growing into devops', function () {
    $en = __('portfolio.skills_subtitle', [], 'en');

    expect($en)->toContain('Backend')->and($en)->toContain('DevOps');
});
