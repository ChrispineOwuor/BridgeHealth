<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("user/profile", [UserController::class, 'index']);
    Route::get('auth/logout', [UserController::class, 'logout']);
});
Route::prefix('pass/res')->middleware(['cookie-token'])->group(function () {
    Route::post('/request-otp', [PatientController::class, 'requestOtp']);
    Route::post('/verify-otp', [PatientController::class, 'verifyOtp']);
    Route::post('/new-pass', [PatientController::class, 'newPswd']);
});
Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/pa/register', [UserController::class, 'register']);
    Route::post('/doc/register', [UserController::class, 'registerDoc']);
    Route::post('/adm/register', [UserController::class, 'registerAdm']);
});

Route::prefix('patient')->middleware(['auth:sanctum', 'patient-auth'])->group(function () {
    Route::post('/add-record', [PatientController::class, 'addRecord']);
    Route::post('/book-appointment', [PatientController::class, 'makeBooking']);
    Route::get('/records', [PatientController::class, 'allRecords']);
    Route::get('/record/{id}', [PatientController::class, 'show']);
    Route::delete('/record/{id}', [PatientController::class, 'destroy']);
    Route::post('/chat', [PatientController::class, 'reminders']);
    Route::get('/symptoms', [PatientController::class, 'getSymptoms']);
    Route::get('/doctors', [PatientController::class, 'getDoctors']);
    Route::get('/dash/data', [PatientController::class, 'dashboardAction']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin-auth'])->group(function () {
    Route::get('/all/system/records', [AdminsController::class, 'index']);
    Route::get('/system/users', [AdminsController::class, 'show']);
    Route::delete('/user/{id}', [AdminsController::class, 'destroy']);
});

Route::prefix('doctor')->middleware(['auth:sanctum', 'doctor-auth'])->group(function () {
    Route::get('/all/medical/records', [DoctorsController::class, 'medicalRecords']);
    Route::get('/alerts', [DoctorsController::class, 'alerts']);
    Route::get('/appointments', [DoctorsController::class, 'appointments']);
    Route::get('/medical-record/{id}', [DoctorsController::class, 'record']);
    Route::post('/recommendation', [DoctorsController::class, 'recommend']);
    Route::post('/add-symptom', [DoctorsController::class, 'addSymptom']);
    Route::put('/appointments/close', [DoctorsController::class, 'closeAppoint']);
    Route::get('/dash/data', [DoctorsController::class, 'dashData']);
});

Route::prefix('analytics')->middleware(['auth:sanctum', 'admin-auth'])->group(function () {
    Route::get('/all/records', [AnalyticsController::class, 'index']);
});
