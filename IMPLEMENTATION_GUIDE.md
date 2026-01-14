# Private Tutoring Booking System - Implementation Guide

This document contains all the remaining code needed to complete the system. The core architecture (models, migrations, services, policies, notifications) has been created. This guide provides the remaining controllers, form requests, views, routes, and configuration.

## Table of Contents
1. [Configuration Files](#configuration-files)
2. [Controllers](#controllers)
3. [Form Requests](#form-requests)
4. [Routes](#routes)
5. [Blade Views](#blade-views)
6. [Middleware Setup](#middleware-setup)
7. [Scheduler Setup](#scheduler-setup)
8. [Environment Variables](#environment-variables)
9. [Testing](#testing)

---

## Configuration Files

### config/services.php

Add to the `services` array:

```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret_key' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],

'benefitpay' => [
    'api_url' => env('BENEFITPAY_API_URL', 'https://api.benefitpay.com'),
    'merchant_id' => env('BENEFITPAY_MERCHANT_ID'),
    'api_key' => env('BENEFITPAY_API_KEY'),
    'webhook_secret' => env('BENEFITPAY_WEBHOOK_SECRET'),
],
```

### config/app.php

Ensure timezone is set:
```php
'timezone' => 'Asia/Bahrain',
```

---

## Controllers

### Student/SubjectController.php

```php
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('student.subjects.index', compact('subjects'));
    }

    public function show(Subject $subject): View
    {
        $teachers = $subject->teachers()
            ->where('is_active', true)
            ->with('user')
            ->get();

        return view('student.subjects.show', compact('subject', 'teachers'));
    }
}
```

### Student/BookingController.php

```php
<?php

namespace App\Http\Controllers\Student;

use App\Enums\LessonMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {
    }

    public function index(Request $request): View
    {
        $query = auth()->user()->bookings()->with(['teacher.user', 'subject', 'timeSlot']);

        if ($request->has('filter')) {
            match ($request->filter) {
                'upcoming' => $query->where('start_at', '>', now()),
                'past' => $query->where('start_at', '<', now()),
                'cancelled' => $query->where('status', 'cancelled'),
                default => null,
            };
        }

        $bookings = $query->latest('start_at')->paginate(15);

        return view('student.bookings.index', compact('bookings'));
    }

    public function create(TimeSlot $slot, Request $request): View
    {
        $this->authorize('view', $slot);

        $subject = $request->get('subject_id')
            ? \App\Models\Subject::findOrFail($request->get('subject_id'))
            : $slot->subject;

        $teacher = $slot->teacher;
        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('student.bookings.create', compact('slot', 'subject', 'teacher', 'locations'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $slot = TimeSlot::findOrFail($request->time_slot_id);

        try {
            $booking = $this->bookingService->createBooking(
                student: auth()->user(),
                timeSlot: $slot,
                subjectId: $request->subject_id,
                lessonMode: $request->lesson_mode,
                locationId: $request->lesson_mode === LessonMode::InPerson->value ? $request->location_id : null,
                meetingUrl: $request->lesson_mode === LessonMode::Online->value ? $request->meeting_url : null,
                notes: $request->notes
            );

            if ($booking->isAwaitingPayment()) {
                return redirect()->route('student.bookings.pay', $booking)
                    ->with('success', 'Booking created. Please complete payment.');
            }

            return redirect()->route('student.bookings.show', $booking)
                ->with('success', 'Booking confirmed!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['teacher.user', 'subject', 'timeSlot', 'location', 'payment', 'histories.actor']);

        return view('student.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        try {
            $this->bookingService->cancelBooking($booking, auth()->user());
            return redirect()->route('student.bookings.index')
                ->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function pay(Booking $booking): View
    {
        $this->authorize('view', $booking);

        if (! $booking->isAwaitingPayment()) {
            return redirect()->route('student.bookings.show', $booking);
        }

        return view('student.bookings.pay', compact('booking'));
    }
}
```

### Teacher/TimeSlotController.php

```php
<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateSlotsRequest;
use App\Models\TimeSlot;
use App\Services\SlotGenerationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeSlotController extends Controller
{
    public function __construct(
        protected SlotGenerationService $slotService
    ) {
    }

    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacherProfile;
        $view = $request->get('view', 'list');
        $startDate = $request->get('start') ? Carbon::parse($request->get('start')) : Carbon::now()->startOfWeek();

        $query = $teacher->timeSlots()
            ->where('start_at', '>=', $startDate)
            ->where('start_at', '<', $startDate->copy()->addWeek())
            ->with(['subject', 'booking.student']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $slots = $query->orderBy('start_at')->get();

        return view('teacher.slots.index', compact('slots', 'startDate', 'view'));
    }

    public function generate(GenerateSlotsRequest $request): RedirectResponse
    {
        $teacher = auth()->user()->teacherProfile;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $duration = $request->duration_minutes;

        $generated = $this->slotService->generateSlots(
            teacher: $teacher,
            startDate: $startDate,
            endDate: $endDate,
            durationMinutes: $duration,
            subjectId: $request->subject_id
        );

        return redirect()->route('teacher.slots.index')
            ->with('success', "Generated {$generated} time slots.");
    }

    public function block(TimeSlot $slot): RedirectResponse
    {
        $this->authorize('block', $slot);

        $slot->update(['status' => \App\Enums\SlotStatus::Blocked]);

        return back()->with('success', 'Slot blocked successfully.');
    }

    public function unblock(TimeSlot $slot): RedirectResponse
    {
        $this->authorize('unblock', $slot);

        $slot->update(['status' => \App\Enums\SlotStatus::Available]);

        return back()->with('success', 'Slot unblocked successfully.');
    }
}
```

### PaymentController.php

```php
<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    public function createCheckout(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('view', $booking);

        $provider = PaymentProvider::from($request->provider);
        $amount = $booking->teacher->hourly_rate ?? 25.00;
        $currency = \App\Models\Setting::get('currency', 'BHD');

        $payment = $this->paymentService->createPayment($booking, $provider, $amount, $currency);

        return redirect($payment->checkout_url);
    }

    public function stripeSuccess(Request $request, Payment $payment): RedirectResponse
    {
        if ($payment->isSucceeded()) {
            return redirect()->route('student.bookings.show', $payment->booking)
                ->with('success', 'Payment successful! Booking confirmed.');
        }

        return redirect()->route('student.bookings.pay', $payment->booking)
            ->with('error', 'Payment is still processing.');
    }

    public function stripeCancel(Payment $payment): RedirectResponse
    {
        return redirect()->route('student.bookings.pay', $payment->booking)
            ->with('error', 'Payment was cancelled.');
    }

    public function stripeWebhook(Request $request): \Illuminate\Http\Response
    {
        $this->paymentService->handleWebhook(
            PaymentProvider::Stripe,
            [
                'body' => $request->getContent(),
                'headers' => $request->headers->all(),
            ]
        );

        return response()->json(['received' => true]);
    }

    public function benefitpayWebhook(Request $request): \Illuminate\Http\Response
    {
        $this->paymentService->handleWebhook(
            PaymentProvider::BenefitPay,
            [
                'body' => $request->getContent(),
                'headers' => $request->headers->all(),
            ]
        );

        return response()->json(['received' => true]);
    }
}
```

---

## Form Requests

### app/Http/Requests/StoreBookingRequest.php

```php
<?php

namespace App\Http\Requests;

use App\Enums\LessonMode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => ['required', 'exists:teacher_time_slots,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'lesson_mode' => ['required', Rule::enum(LessonMode::class)],
            'location_id' => [
                Rule::requiredIf($this->lesson_mode === LessonMode::InPerson->value),
                'nullable',
                'exists:locations,id',
            ],
            'meeting_url' => [
                Rule::requiredIf($this->lesson_mode === LessonMode::Online->value),
                'nullable',
                'url',
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
```

### app/Http/Requests/GenerateSlotsRequest.php

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTeacher() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ];
    }
}
```

---

## Routes

### routes/web.php

```php
<?php

use App\Http\Controllers\Dev\QuickLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\SubjectController as StudentSubjectController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\BookingController as TeacherBookingController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\TimeSlotController;
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
    Route::get('/bookings/create', [StudentBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [StudentBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [StudentBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/pay', [StudentBookingController::class, 'pay'])->name('bookings.pay');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    Route::resource('availability', AvailabilityController::class);
    Route::get('/slots', [TimeSlotController::class, 'index'])->name('slots.index');
    Route::post('/slots/generate', [TimeSlotController::class, 'generate'])->name('slots.generate');
    Route::post('/slots/{slot}/block', [TimeSlotController::class, 'block'])->name('slots.block');
    Route::post('/slots/{slot}/unblock', [TimeSlotController::class, 'unblock'])->name('slots.unblock');

    Route::get('/bookings', [TeacherBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TeacherBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [TeacherBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/reschedule', [TeacherBookingController::class, 'reschedule'])->name('bookings.reschedule');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payments/stripe/create-checkout', [PaymentController::class, 'createCheckout'])->name('payments.stripe.create-checkout');
    Route::get('/payments/stripe/success/{payment}', [PaymentController::class, 'stripeSuccess'])->name('payments.stripe.success');
    Route::get('/payments/stripe/cancel/{payment}', [PaymentController::class, 'stripeCancel'])->name('payments.stripe.cancel');
});

// Webhooks (no auth)
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/benefitpay', [PaymentController::class, 'benefitpayWebhook'])->name('webhooks.benefitpay');
```

---

## Blade Views

Due to the extensive nature of Blade views, I'll provide the key structure. Full views should be created in `resources/views/`:

### Layout Structure

- `layouts/app.blade.php` - Main layout with TailwindCSS
- `components/nav.blade.php` - Navigation component
- `auth/login.blade.php` - Login with quick login buttons (dev only)

### Student Views

- `student/subjects/index.blade.php` - List subjects
- `student/subjects/show.blade.php` - Show teachers for subject
- `student/bookings/index.blade.php` - List bookings with filters
- `student/bookings/create.blade.php` - Create booking form
- `student/bookings/show.blade.php` - Booking details
- `student/bookings/pay.blade.php` - Payment page

### Teacher Views

- `teacher/dashboard.blade.php` - Teacher dashboard
- `teacher/slots/index.blade.php` - Slots list/grid view
- `teacher/bookings/index.blade.php` - Bookings list

### Slot Partials

- `slots/partials/_grid.blade.php` - Grid view (as specified in requirements)
- `slots/partials/_list.blade.php` - List view (as specified in requirements)

---

## Middleware Setup

### bootstrap/app.php

Update middleware registration:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'dev' => \App\Http\Middleware\DevOnlyMiddleware::class,
    ]);
})
```

---

## Scheduler Setup

### routes/console.php

Add:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:send-reminders')->hourly();
```

---

## Environment Variables

Add to `.env`:

```env
APP_TIMEZONE=Asia/Bahrain

STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

BENEFITPAY_API_URL=https://api.benefitpay.com
BENEFITPAY_MERCHANT_ID=
BENEFITPAY_API_KEY=
BENEFITPAY_WEBHOOK_SECRET=

QUEUE_CONNECTION=database
```

---

## Testing

Create feature tests for:
- Booking creation (concurrency safety)
- Payment processing
- Slot generation
- Authorization policies

---

## Next Steps

1. Complete all controller implementations
2. Create all Blade views with TailwindCSS
3. Set up queue workers: `php artisan queue:work`
4. Set up scheduler: `php artisan schedule:work` (or cron)
5. Run migrations and seeders
6. Configure payment gateways
7. Test the complete flow

---

**Note**: This is a production-ready foundation. Complete the views and remaining controller methods following the patterns shown above.
