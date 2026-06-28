<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mata Kuliah
    Route::resource('courses', CourseController::class)->only(['index', 'store', 'update', 'destroy']);

    // Dosen
    Route::resource('lecturers', LecturerController::class)->only(['index', 'store', 'update', 'destroy']);

    // Proyek
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');

    // Tugas
    Route::resource('tasks', TaskController::class);

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Riwayat Aktivitas
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // Manajemen Pengguna
    Route::resource('users', UserManagementController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
