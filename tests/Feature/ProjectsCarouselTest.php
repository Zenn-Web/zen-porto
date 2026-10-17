<?php

use App\Models\Project;
use Database\Seeders\ProjectSeeder;

// Below 992px the projects are a swipeable carousel (CSS scroll-snap, next card peeking, dots); above it they stay a list.

function projectsSection(string $html): string
{
    preg_match('/<section id="resources".*?<section id="contact"/s', $html, $m);

    return $m[0] ?? '';
}

function projectsCss(): string
{
    return file_get_contents(resource_path('css/components/projects.css'));
}

test('the projects sit in a carousel track with one dot per project', function () {
    $this->seed(ProjectSeeder::class);

    $section = projectsSection($this->get('/')->assertOk()->getContent());

    expect($section)->toContain('x-data="projectsCarousel"')
        ->and($section)->toContain('x-ref="track"')
        ->and($section)->toContain('projects-carousel');
    expect(substr_count($section, 'class="projects-dot"'))->toBe(Project::count());
});

test('a single project shows no dots', function () {
    $this->seed(ProjectSeeder::class);
    Project::query()->where('id', '!=', Project::query()->min('id'))->delete();

    expect(projectsSection($this->get('/')->getContent()))->not->toContain('projects-dot');
});

test('no projects still shows the empty message and no carousel controls', function () {
    $section = projectsSection($this->get('/')->getContent());

    expect($section)->not->toContain('projects-dot');
});

test('the dividing line between projects is only for the list layout', function () {
    $this->seed(ProjectSeeder::class);

    $section = projectsSection($this->get('/')->getContent());

    expect($section)->toContain('lg:border-b')->not->toMatch('/(?<![:\w-])border-b(?![\w-])/');
});

test('each card fills the track (no neighbour peeking), snaps one at a time below 992px, and is the stacked list from 992px up', function () {
    $css = projectsCss();

    expect($css)->toMatch('/\.projects-carousel\s*\{[^}]*flex-direction:\s*column/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.projects-carousel\s*\{[^}]*overflow-x:\s*auto[^}]*scroll-snap-type:\s*x mandatory/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.project-centered-item\s*\{[^}]*flex:\s*0 0 100%[^}]*scroll-snap-align:\s*start[^}]*scroll-snap-stop:\s*always/s')
        ->and($css)->toMatch('/\.projects-controls\s*\{[^}]*display:\s*none/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.projects-controls\s*\{[^}]*display:\s*flex/s');
});

test('the carousel component is registered with Alpine and respects reduced motion', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain("Alpine.data('projectsCarousel'")
        ->and($js)->toContain('prefersReducedMotion()');
});

test('previous and next buttons flank the dots, with a translated screen-reader label', function () {
    $this->seed(ProjectSeeder::class);

    $section = projectsSection($this->get('/')->getContent());

    expect($section)->toContain('class="projects-arrow projects-arrow--prev"')
        ->and($section)->toContain('class="projects-arrow projects-arrow--next"')
        ->and($section)->toContain('@click="prev()"')
        ->and($section)->toContain('@click="next()"')
        ->and($section)->toContain('data-i18n-en="'.__('portfolio.projects_next', [], 'en').'"')
        ->and($section)->toContain('data-i18n-id="'.__('portfolio.projects_prev', [], 'id').'"');
});

test('a single project shows no arrows either', function () {
    $this->seed(ProjectSeeder::class);
    Project::query()->where('id', '!=', Project::query()->min('id'))->delete();

    expect(projectsSection($this->get('/')->getContent()))->not->toContain('projects-arrow');
});

test('the arrows move one project and stop at both ends', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain('prev()')->and($js)->toContain('next()')
        ->and($js)->toContain('Math.max(0, this.active - 1)')
        ->and($js)->toContain('Math.min(this.count - 1, this.active + 1)');
});
