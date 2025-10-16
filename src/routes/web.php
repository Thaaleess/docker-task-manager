<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tasks.home');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/home', [TaskController::class, 'home'])->name('tasks.home');
Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

Route::get('/tasks/pending', [TaskController::class, 'pendingList'])->name('tasks.pending');
Route::get('/tasks/in_progress', [TaskController::class, 'inProgressList'])->name('tasks.in_progress');
Route::get('/tasks/completed', [TaskController::class, 'completedList'])->name('tasks.completed');