<?php

use App\Http\Controllers\Dev\QuickLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\BookingController as TeacherBookingController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TimeSlotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Middleware\DevOnlyMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// Dev Quick Login (local only)
Route::middleware([DevOnlyMiddleware::class])->group(function () {
    Route::get('/dev/quick-login/admin', [QuickLoginController::class, 'admin'])->name('dev.quick-login.admin');
    Route::get('/dev/quick-login/teacher', [QuickLoginController::class, 'teacher'])->name('dev.quick-login.teacher');
    Route::get('/dev/quick-login/student', [QuickLoginController::class, 'student'])->name('dev.quick-login.student');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/subjects', [StudentSubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [StudentSubjectController::class, 'show'])->name('subjects.show');
    Route::get('/teachers/{teacher}/slots', [StudentSubjectController::class, 'slots'])->name('teachers.slots');

    Route::get('/bookings', [StudentBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{slot}', [StudentBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [StudentBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [StudentBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/pay', [StudentBookingController::class, 'pay'])->name('bookings.pay');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    Route::get('/subjects', [\App\Http\Controllers\Teacher\SubjectController::class, 'index'])->name('subjects.index');
    Route::put('/subjects', [\App\Http\Controllers\Teacher\SubjectController::class, 'update'])->name('subjects.update');

    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');

    Route::get('/slots', [TimeSlotController::class, 'index'])->name('slots.index');
    Route::post('/slots/generate', [TimeSlotController::class, 'generate'])->name('slots.generate');
    Route::post('/slots/{slot}/block', [TimeSlotController::class, 'block'])->name('slots.block');
    Route::post('/slots/{slot}/unblock', [TimeSlotController::class, 'unblock'])->name('slots.unblock');

    Route::get('/bookings', [TeacherBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TeacherBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [TeacherBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/reschedule', [TeacherBookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::patch('/bookings/{booking}/meeting-url', [TeacherBookingController::class, 'updateMeetingUrl'])->name('bookings.update-meeting-url');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payments/stripe/create-checkout', [PaymentController::class, 'createCheckout'])->name('payments.stripe.create-checkout');
    Route::get('/payments/stripe/success/{payment}', [PaymentController::class, 'stripeSuccess'])->name('payments.stripe.success');
    Route::get('/payments/stripe/cancel/{payment}', [PaymentController::class, 'stripeCancel'])->name('payments.stripe.cancel');
    
    // Test payment (local/debug only)
    Route::middleware([DevOnlyMiddleware::class])->group(function () {
        Route::post('/payments/test/{booking}/complete', [PaymentController::class, 'testComplete'])->name('payments.test.complete');
    });
});

// Webhooks (no auth)
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/benefitpay', [PaymentController::class, 'benefitpayWebhook'])->name('webhooks.benefitpay');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
