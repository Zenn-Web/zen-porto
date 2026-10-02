<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman home
Route::get('/', function () {
    $projects = \App\Models\Project::all();

    return view('pages.home', compact('projects'));
});

// Route untuk detail project
Route::get('/project/{project:slug}', [ProjectController::class, 'show'])->name('project.show');

// Route untuk contact form
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// Route untuk switch bahasa (background fetch)
Route::post('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
