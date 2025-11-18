<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CommentController;

// API маршруты для версии 2.0
Route::prefix('v2')->group(function () {
    // Категории
    Route::apiResource('categories', CategoryController::class);

    // Контакты
    Route::apiResource('contacts', ContactController::class);

    // Комментарии
    Route::apiResource('comments', CommentController::class);

    // Дополнительные маршруты для работы со связями
    Route::get('categories/{id}/contacts', [CategoryController::class, 'index'])
        ->name('categories.contacts');

    Route::get('contacts/{id}/comments', [ContactController::class, 'show'])
        ->name('contacts.comments');
});

