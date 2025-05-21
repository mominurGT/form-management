<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FormConfigurationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/configure', [FormConfigurationController::class, 'index'])->name('configure');
    Route::post('/save-json', [FormConfigurationController::class, 'store'])->name('savejson');
    Route::get('/view', [FormConfigurationController::class, 'viewFormList'])->name('view');
    Route::get('/forms/{id}', [FormConfigurationController::class, 'show'])->name('show');

    Route::get('/form-edit/{id}', [FormConfigurationController::class, 'editForm'])->name('form.edit');
    Route::put('/form-edit/{id}', [FormConfigurationController::class, 'updateForm'])->name('form.update');

    Route::get('/form-builder/{id}', [FormConfigurationController::class, 'edit'])->name('form-builder');
    Route::put('/form-builder/{id}', [FormConfigurationController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Additional Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
