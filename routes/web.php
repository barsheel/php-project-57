<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource('task_statuses', TaskStatusController::class)
    ->except(['index','show'])
    ->middleware('auth');

Route::resource('task_statuses', TaskStatusController::class)
    ->only(['index', 'show']);

Route::resource('tasks', TaskController::class)
    ->except(['index','show'])
    ->middleware('auth');

Route::resource('tasks', TaskController::class)
    ->only(['index', 'show']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
