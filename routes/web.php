<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';



Route::get('dashboard', [\App\Http\Controllers\MyContentsController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('my.contents');

Route::get('watch/{content:slug}', [\App\Http\Controllers\MyContentsController::class, 'watch'])
    ->middleware('auth')
    ->name('watch.video');

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



Route::get('resources/{code}/{video}', function($code, $video) {
    $video = $code . '/' . $video;

    return Storage::disk('videos_encoded')
            ->response(
                $video,
                null,
                [
                    'Content-Type' => 'application/x-mpegURL',
                    'isHls' => true
                ]
            );

})->name('stream_player');
