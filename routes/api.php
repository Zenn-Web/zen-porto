<?php

use App\Models\Project;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public, read-only endpoints. Rate limited per IP by the "api" limiter
| defined in AppServiceProvider. The former POST /api/contact endpoint was
| removed: it had no consumer and only echoed submitted personal data.
|
*/

Route::middleware('throttle:api')->group(function () {
    // 1. GET: List Semua Projects Portofolio
    Route::get('/projects', function () {
        return response()->json([
            'success' => true,
            'message' => 'List data projek portofolio',
            'data' => Project::all(),
        ]);
    });

    // 2. GET: Detail Project Berdasarkan Slug
    Route::get('/projects/{slug}', function ($slug) {
        $project = Project::where('slug', $slug)->first();

        if (! $project) {
            return response()->json([
                'success' => false,
                'message' => 'Projek tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    });
});
