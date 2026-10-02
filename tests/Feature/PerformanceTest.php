<?php

use App\Models\Project;
use Illuminate\Support\Facades\DB;

/*
 * Deterministic performance guards. Wall-clock thresholds were removed because
 * they depend on machine load and cold caches; query counts do not.
 */

function queriesExecutedDuring(callable $callback): int
{
    DB::flushQueryLog();
    DB::enableQueryLog();

    $callback();

    $count = count(DB::getQueryLog());
    DB::disableQueryLog();

    return $count;
}

function createProjects(int $count, string $prefix): void
{
    for ($i = 1; $i <= $count; $i++) {
        Project::create([
            'title' => "Project {$i}",
            'slug' => "{$prefix}-{$i}",
            'year' => '2026',
            'tech_stack' => ['Laravel', 'Tailwind'],
        ]);
    }
}

test('home page query count does not grow with the number of projects', function () {
    createProjects(1, 'few');
    $withOneProject = queriesExecutedDuring(fn () => $this->get('/')->assertOk());

    createProjects(9, 'many');
    $withTenProjects = queriesExecutedDuring(fn () => $this->get('/')->assertOk());

    expect($withOneProject)->toBeGreaterThan(0);
    expect($withTenProjects)->toBe($withOneProject);
});

test('project detail page uses a constant number of queries', function () {
    createProjects(10, 'detail');

    $first = queriesExecutedDuring(fn () => $this->get('/project/detail-1')->assertOk());
    $last = queriesExecutedDuring(fn () => $this->get('/project/detail-10')->assertOk());

    expect($first)->toBe($last);
    expect($first)->toBeLessThanOrEqual(2);
});

test('invalid contact submissions are rejected without touching the database', function () {
    $queries = queriesExecutedDuring(
        fn () => $this->post(route('contact.store'), [])->assertSessionHasErrors()
    );

    expect($queries)->toBe(0);
});
