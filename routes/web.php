<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FormController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Форма для отправки данных
Route::get('/form', [FormController::class, 'showForm'])->name('form.show');
Route::post('/form', [FormController::class, 'submitForm'])->name('form.submit');

// Страница с данными
Route::get('/data', [FormController::class, 'showData'])->name('data.show');

// Страница управления через API
Route::get('/api', function () {
    return view('api');
})->name('api');
