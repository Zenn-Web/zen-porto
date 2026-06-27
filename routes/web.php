<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;

// Route untuk halaman home
Route::get('/', function () {
    $projects = \App\Models\Project::all();
    return view('pages.home', compact('projects'));
});

// Route untuk detail project
Route::get('/project/{project:slug}', [ProjectController::class, 'show'])->name('project.show');

// Route untuk contact form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
