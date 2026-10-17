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

test('the track scrolls and snaps below 992px and is the stacked list from 992px up', function () {
    $css = projectsCss();

    expect($css)->toMatch('/\.projects-carousel\s*\{[^}]*flex-direction:\s*column/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.projects-carousel\s*\{[^}]*overflow-x:\s*auto[^}]*scroll-snap-type:\s*x mandatory/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.project-centered-item\s*\{[^}]*flex:\s*0 0 86%[^}]*scroll-snap-align:\s*start/s')
        ->and($css)->toMatch('/\.projects-dots\s*\{[^}]*display:\s*none/s')
        ->and($css)->toMatch('/@media \(max-width: 991px\)\s*\{.*?\.projects-dots\s*\{[^}]*display:\s*flex/s');
});

test('the carousel component is registered with Alpine and respects reduced motion', function () {
    $js = file_get_contents(resource_path('js/alpine-init.js'));

    expect($js)->toContain("Alpine.data('projectsCarousel'")
        ->and($js)->toContain('prefersReducedMotion()');
});
