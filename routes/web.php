<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Dev\QuickLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\BookingController as TeacherBookingController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TimeSlotController;
use App\Http\Middleware\DevOnlyMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $dashboardRoute = match (true) {
            $user->isAdmin() => 'admin.dashboard',
            $user->isTeacher() => 'teacher.dashboard',
            $user->isStudent() => 'student.dashboard',
            default => 'login',
        };

        return redirect()->route($dashboardRoute);
    }

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
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/subjects', [StudentSubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [StudentSubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/courses', [\App\Http\Controllers\Student\SubjectCoursesController::class, 'index'])->name('subjects.courses');
    Route::get('/teachers/{teacher}/slots', [StudentSubjectController::class, 'slots'])->name('teachers.slots');

    Route::get('/bookings', [StudentBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{slot}', [StudentBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [StudentBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [StudentBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/pay', [StudentBookingController::class, 'pay'])->name('bookings.pay');

    // Course Routes
    Route::get('/courses/{course:slug}', [\App\Http\Controllers\Student\CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/purchase', [\App\Http\Controllers\CoursePurchasePaymentController::class, 'purchase'])->name('courses.purchase');
    Route::get('/my-courses', [\App\Http\Controllers\Student\MyCoursesController::class, 'index'])->name('my-courses.index');
    Route::get('/my-courses/{course:slug}/learn', [\App\Http\Controllers\Student\LearningController::class, 'learn'])->name('my-courses.learn');
    Route::get('/my-courses/{course:slug}/lesson/{lesson}', [\App\Http\Controllers\Student\LearningController::class, 'showLesson'])->name('my-courses.lesson');
    Route::post('/lessons/{lesson}/progress', [\App\Http\Controllers\Student\LessonProgressController::class, 'update'])->name('lessons.progress');
    Route::post('/lessons/{lesson}/complete', [\App\Http\Controllers\Student\LessonProgressController::class, 'complete'])->name('lessons.complete');
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
    Route::post('/bookings/{booking}/cancel', [TeacherBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/meeting-url', [TeacherBookingController::class, 'updateMeetingUrl'])->name('bookings.update-meeting-url');
    Route::patch('/bookings/{booking}/location', [TeacherBookingController::class, 'updateLocation'])->name('bookings.update-location');

    // Course Routes
    Route::resource('courses', \App\Http\Controllers\Teacher\CourseController::class);
    Route::post('/courses/{course}/publish', [\App\Http\Controllers\Teacher\CourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course}/unpublish', [\App\Http\Controllers\Teacher\CourseController::class, 'unpublish'])->name('courses.unpublish');
    Route::get('/courses/{course}/lessons', [\App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('courses.lessons');
    Route::post('/courses/{course}/lessons', [\App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::get('/courses/{course}/sales', [\App\Http\Controllers\Teacher\CourseSalesController::class, 'index'])->name('courses.sales');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('locations', \App\Http\Controllers\Admin\LocationController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::get('/courses', [\App\Http\Controllers\Admin\CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [\App\Http\Controllers\Admin\CoursesController::class, 'show'])->name('courses.show');
    Route::put('/courses/{course}/toggle-publish', [\App\Http\Controllers\Admin\CoursesController::class, 'togglePublish'])->name('courses.toggle-publish');
    Route::get('/course-sales', [\App\Http\Controllers\Admin\CourseSalesController::class, 'index'])->name('course-sales.index');
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

// Course Webhooks (separate from booking webhooks)
Route::post('/webhooks/stripe/courses', [\App\Http\Controllers\CourseStripeWebhookController::class, 'handle'])->name('webhooks.stripe.courses');
Route::post('/webhooks/benefitpay/courses', [\App\Http\Controllers\CourseBenefitPayWebhookController::class, 'handle'])->name('webhooks.benefitpay.courses');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
