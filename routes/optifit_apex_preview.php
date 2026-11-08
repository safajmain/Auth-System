<?php

use App\Http\Controllers\OptiFitApexPreviewController;
use Illuminate\Support\Facades\Route;

/**
 * APEX preview runs only with APP_ENV=local. This prevents simulated admin/owner
 * pages from accidentally being presented as real privileged screens online.
 * Include from routes/web.php:
 *
 *     require __DIR__.'/optifit_apex_preview.php';
 */
if (app()->environment('local')) {
    Route::get('/optifit-apex', [OptiFitApexPreviewController::class, 'index'])
        ->name('optifit.apex.preview');
}
