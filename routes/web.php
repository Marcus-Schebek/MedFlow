<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.create');
});
Route::get('/doctors', [DoctorController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.create');
});
Route::get('/appointments/user/{userId}', [AppointmentController::class, 'getUserAppointments']);
Route::put('/appointments/{appointmentId}', [AppointmentController::class, 'updateAppointment']);
Route::delete('/appointments/{appointmentId}', [AppointmentController::class, 'deleteAppointment']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
