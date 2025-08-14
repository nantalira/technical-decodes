<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/jobs/{job}', [PublicController::class, 'showJob'])->name('jobs.show');

// Guest application route (no auth required)
Route::post('/jobs/apply-guest', [PublicController::class, 'applyJobGuest'])->name('jobs.apply.guest');

// Job interaction routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::post('/jobs/{job}/bookmark', [PublicController::class, 'toggleBookmark'])->name('jobs.bookmark');
    Route::post('/jobs/{job}/apply', [PublicController::class, 'applyJob'])->name('jobs.apply');
    Route::get('/saved-jobs', [PublicController::class, 'savedJobs'])->name('jobs.saved');
    Route::get('/applied-jobs', [PublicController::class, 'appliedJobs'])->name('jobs.applied');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected routes with secure session
Route::middleware(['auth', 'secure.session'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    // Session Management
    Route::get('/sessions', [AuthController::class, 'showActiveSessions'])->name('sessions');
    Route::post('/logout-all', [AuthController::class, 'logoutAllDevices'])->name('logout.all');

    // Job Bookmarks
    Route::get('/bookmarks', function () {
        return view('pages.bookmarks');
    })->name('bookmarks');

    // Job Applications
    Route::get('/applications', function () {
        return view('pages.applications');
    })->name('applications');
});

// Admin routes (additional security)
Route::middleware(['auth', 'admin', 'secure.session'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/{user}/delete', [AdminController::class, 'deleteUserConfirm'])->name('users.delete.confirm');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Job Management Routes
    // Job Management Routes
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
    Route::post('/jobs', [AdminController::class, 'createJobPost'])->name('jobs.create');
    Route::get('/jobs/{job}/edit', [AdminController::class, 'editJob'])->name('jobs.edit');
    Route::put('/jobs/{job}', [AdminController::class, 'updateJob'])->name('jobs.update');
    Route::get('/jobs/{job}/delete', [AdminController::class, 'deleteJobConfirm'])->name('jobs.delete.confirm');
    Route::delete('/jobs/{job}', [AdminController::class, 'deleteJob'])->name('jobs.delete');

    // Job Applications Routes
    Route::get('/jobs/{job}/applications', [AdminController::class, 'jobApplications'])->name('job.applications');
    Route::put('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])->name('applications.update.status');
});

// Public job routes (no auth required)
Route::get('/jobs', function () {
    return view('jobs.index');
})->name('jobs.index');

Route::get('/jobs/search', function () {
    return view('jobs.search');
})->name('jobs.search');

Route::get('/jobs/{id}', function ($id) {
    return view('jobs.show', compact('id'));
})->name('jobs.show');
