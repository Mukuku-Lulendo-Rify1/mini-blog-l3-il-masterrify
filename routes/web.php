<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;
Route::get('/', function () {
    return view('posts.index');
});
Route::get('/articles', [MainController::class, 'articles'])
    ->name('articles.index');

Route::get('/articles/{slug}', [MainController::class, 'article'])
    ->name('articles.show');

Route::get('/public/categories', [MainController::class, 'categories'])
    ->name('categories.index');

Route::get('/posts/about', [MainController::class, 'about'])
    ->name('about');

    Route::prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('index');

        // Ici, il y a déjà dashboard comme prefix... Faut plus en rajouter
        Route::get('/dashboard/articles', [DashboardController::class, 'articles'])
            ->name('articles');

        Route::get('/categories', [DashboardController::class, 'categories'])
            ->name('categories');

        Route::get('/utilisateurs', [DashboardController::class, 'users'])
            ->name('users');

        Route::get('/commentaires', [DashboardController::class, 'comments'])
            ->name('comments');

        Route::get('/reglages', [DashboardController::class, 'settings'])
            ->name('settings');
    });