<?php

/*
 * The technologies shown in the Skills section, grouped, in display order.
 *
 * status: 'using' (in real use), 'learning' (being learned), 'planned' (not started yet).
 * Keep it honest: a technology is only 'using' when it really is. `note` (optional) is a translation key
 * suffix (portfolio.skills_note_*) for a short qualifier shown next to the name.
 * The group titles are translated as portfolio.skills_group_{key}.
 */
return [
    // The group open when the page loads: the one that shows the role best (Laravel, Spring Boot...).
    'default_group' => 'frameworks',

    'groups' => [
        ['key' => 'languages', 'items' => [
            ['name' => 'Java', 'status' => 'using'],
            ['name' => 'PHP', 'status' => 'using'],
            ['name' => 'JavaScript', 'status' => 'using'],
            ['name' => 'TypeScript', 'status' => 'planned'],
        ]],
        ['key' => 'frameworks', 'items' => [
            ['name' => 'Spring Boot', 'status' => 'learning'],
            ['name' => 'Laravel', 'status' => 'using'],
            ['name' => 'NestJS', 'status' => 'learning'],
            ['name' => 'Express.js', 'status' => 'learning'],
            // A JavaScript/TypeScript runtime and toolkit, not a framework.
            ['name' => 'Bun', 'status' => 'learning', 'note' => 'runtime'],
        ]],
        ['key' => 'api', 'items' => [
            ['name' => 'REST API', 'status' => 'using'],
            ['name' => 'Bruno', 'status' => 'using'],
            ['name' => 'PHPUnit', 'status' => 'using'],
            ['name' => 'Maven Surefire', 'status' => 'learning'],
            ['name' => 'Jest', 'status' => 'learning'],
            ['name' => 'Playwright', 'status' => 'learning'],
        ]],
        ['key' => 'data', 'items' => [
            ['name' => 'PostgreSQL', 'status' => 'using'],
            ['name' => 'MySQL', 'status' => 'using'],
            ['name' => 'SQLite', 'status' => 'using'],
            ['name' => 'Eloquent ORM', 'status' => 'using'],
            ['name' => 'DBeaver', 'status' => 'learning'],
            ['name' => 'Redis', 'status' => 'learning'],
        ]],
        ['key' => 'tools', 'items' => [
            ['name' => 'Git & GitHub', 'status' => 'using'],
            ['name' => 'VS Code', 'status' => 'using'],
            ['name' => 'IntelliJ IDEA', 'status' => 'using'],
            ['name' => 'Maven', 'status' => 'learning'],
            ['name' => 'Composer', 'status' => 'using'],
            ['name' => 'Vite', 'status' => 'using'],
            ['name' => 'Laragon', 'status' => 'using'],
            ['name' => 'Laravel Herd', 'status' => 'learning'],
            ['name' => 'Jira', 'status' => 'using'],
        ]],
        ['key' => 'devops', 'items' => [
            ['name' => 'Docker', 'status' => 'learning'],
            ['name' => 'GitHub Actions', 'status' => 'learning'],
            ['name' => 'Linux', 'status' => 'learning'],
            ['name' => 'Windows', 'status' => 'using'],
            ['name' => 'Hostinger', 'status' => 'learning'],
            ['name' => 'Laravel Cloud', 'status' => 'learning'],
        ]],
        ['key' => 'frontend', 'items' => [
            ['name' => 'HTML', 'status' => 'using'],
            ['name' => 'CSS', 'status' => 'using'],
            ['name' => 'React', 'status' => 'using'],
            ['name' => 'Tailwind CSS', 'status' => 'using'],
            ['name' => 'Bootstrap', 'status' => 'using'],
        ]],
    ],
];
