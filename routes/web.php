<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Rutas generadas por Breeze (autenticación)
require __DIR__ . '/auth.php';

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Dashboard adaptado según el rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil (generado por Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para todos los usuarios autenticados
    Route::resource('vacation-requests', VacationRequestController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/calendar/personal', [CalendarController::class, 'personal'])->name('calendar.personal');

    // Rutas solo para gerentes y administradores
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/requests/approval', [VacationRequestController::class, 'approvalList'])->name('requests.approval');
        Route::put('/requests/{request}/approve', [VacationRequestController::class, 'approve'])->name('requests.approve');
        Route::put('/requests/{request}/reject', [VacationRequestController::class, 'reject'])->name('requests.reject');
        Route::get('/calendar/team', [CalendarController::class, 'team'])->name('calendar.team');
    });

    // Rutas exclusivas para administradores
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class);
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
