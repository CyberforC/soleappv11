<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteAuthorController;
use App\Http\Controllers\RatingAuthorController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function()
{
    Route::controller(AuthorController::class)->group(function()
    {
        Route::get('/authors', 'index');
        Route::post('/authors', 'store');
        Route::get('/authors/{id}', 'show');
        Route::put('/authors/{id}', 'update');
        Route::delete('/authors/{id}', 'destroy');
    });

    Route::controller(ProfileController::class)->group(function()
    {
        Route::post('/profiles', 'store');
        Route::put('/profiles/{id}', 'update');
    });

    Route::controller(NoteAuthorController::class)->group(function()
    {
        Route::get('/authors/{id}/notes', 'index');
        Route::post('/authors/notes', 'store');
        Route::put('/authors/notes/{id}', 'update');
        Route::delete('/authors/notes/{id}', 'destroy');
    });

    Route::controller(RatingAuthorController::class)->group(function()
    {
        Route::post('/authors/ratings', 'store');
        Route::get('/authors/{id}/ratings', 'show');
        Route::put('/authors/ratings/{id}', 'update');
    });
});
