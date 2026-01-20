<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Dev\QuickLoginController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\BookingController as TeacherBookingController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TimeSlotController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\DevOnlyMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Locale switching
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/home', function () {
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
})->name('home');

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

    // Review Routes
    Route::post('/reviews', [\App\Http\Controllers\Student\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/teachers/{teacher}', [\App\Http\Controllers\Student\TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/reviews', [\App\Http\Controllers\Student\TeacherController::class, 'reviews'])->name('teachers.reviews');

    // Message Routes
    Route::get('/messages', [\App\Http\Controllers\Student\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [\App\Http\Controllers\Student\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/start', [\App\Http\Controllers\Student\MessageController::class, 'startConversation'])->name('messages.start');
    Route::get('/messages/{conversation}', [\App\Http\Controllers\Student\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [\App\Http\Controllers\Student\MessageController::class, 'store'])->name('messages.store');

    // Resource Routes
    Route::get('/resources', [\App\Http\Controllers\Student\ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/{resource}/download', [\App\Http\Controllers\Student\ResourceController::class, 'download'])->name('resources.download');
    Route::get('/message-attachments/{attachment}/download', [\App\Http\Controllers\Student\ResourceController::class, 'downloadAttachment'])->name('message-attachments.download');

    // Support Ticket Routes
    Route::get('/support-tickets', [\App\Http\Controllers\Student\SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('/support-tickets/create', [\App\Http\Controllers\Student\SupportTicketController::class, 'create'])->name('support-tickets.create');
    Route::post('/support-tickets', [\App\Http\Controllers\Student\SupportTicketController::class, 'store'])->name('support-tickets.store');
    Route::get('/support-tickets/{supportTicket}', [\App\Http\Controllers\Student\SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{supportTicket}/reply', [\App\Http\Controllers\Student\SupportTicketController::class, 'reply'])->name('support-tickets.reply');
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

    // Message Routes
    Route::get('/messages', [\App\Http\Controllers\Teacher\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [\App\Http\Controllers\Teacher\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [\App\Http\Controllers\Teacher\MessageController::class, 'store'])->name('messages.store');

    // Resource Routes
    Route::get('/resources', [\App\Http\Controllers\Teacher\ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/create', [\App\Http\Controllers\Teacher\ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [\App\Http\Controllers\Teacher\ResourceController::class, 'store'])->name('resources.store');
    Route::delete('/resources/{resource}', [\App\Http\Controllers\Teacher\ResourceController::class, 'destroy'])->name('resources.destroy');
    Route::get('/message-attachments/{attachment}/download', [\App\Http\Controllers\Teacher\ResourceController::class, 'downloadAttachment'])->name('message-attachments.download');
});

// API Routes
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/user/online-status', function () {
        try {
            $user = auth()->user();

            if (! $user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Update last_seen_at directly
            $user->last_seen_at = now();
            $user->saveQuietly();

            // Get fresh instance to ensure we have the updated value
            $user = $user->fresh();

            return response()->json([
                'status' => 'updated',
                'is_online' => $user->isOnline(),
                'last_seen_at' => $user->last_seen_at?->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('locations', \App\Http\Controllers\Admin\LocationController::class);
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('students.show');

    // User Management Routes
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
    Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/courses', [\App\Http\Controllers\Admin\CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [\App\Http\Controllers\Admin\CoursesController::class, 'show'])->name('courses.show');
    Route::put('/courses/{course}/toggle-publish', [\App\Http\Controllers\Admin\CoursesController::class, 'togglePublish'])->name('courses.toggle-publish');
    Route::get('/course-sales', [\App\Http\Controllers\Admin\CourseSalesController::class, 'index'])->name('course-sales.index');

    // Review Management Routes
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Support Ticket Routes
    Route::get('/support-tickets', [\App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('support-tickets.index');
    Route::get('/support-tickets/{supportTicket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('support-tickets.show');
    Route::post('/support-tickets/{supportTicket}/assign', [\App\Http\Controllers\Admin\SupportTicketController::class, 'assign'])->name('support-tickets.assign');
    Route::post('/support-tickets/{supportTicket}/status', [\App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');
    Route::post('/support-tickets/{supportTicket}/reply', [\App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('support-tickets.reply');
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
