<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



Route::prefix('media')
    ->name('media.')
    ->middleware('auth')
    ->group(function() {
        Route::resource('contents', \App\Http\Controllers\Media\ContentController::class);

        Route::get(
            'contents/{content}/videos/upload',
            [\App\Http\Controllers\Media\VideosController::class, 'index']
        )->name('contents.videos.upload');

        Route::post(
            'contents/{content}/videos/upload',
            [\App\Http\Controllers\Media\VideosController::class, 'store']
        )->name('contents.videos.upload.store');

        Route::patch(
            'contents/{content}/videos/{video}',
            [\App\Http\Controllers\Media\VideosController::class, 'update']
        )->name('contents.videos.upload.update');

        Route::delete(
            'contents/{content}/videos/{video}/destroy',
            [\App\Http\Controllers\Media\VideosController::class, 'destroy']
        )->name('contents.videos.upload.destroy');

        Route::put(
            'contents/{content}/videos/{video}/process/chunck',
            [\App\Http\Controllers\Media\VideosController::class, 'processChunck']
        )->name('contents.videos.upload.process.chunck');
    });


