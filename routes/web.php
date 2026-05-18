<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Lahat ng routes dito ay kailangan ng login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (galing sa Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tasks
    Route::resource('tasks', TaskController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
        ->name('tasks.toggle');

    // Categories
    Route::resource('categories', CategoryController::class)->only([
        'index', 'store', 'destroy'
    ]);
});

require __DIR__.'/auth.php';