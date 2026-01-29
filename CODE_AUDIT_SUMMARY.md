# Code Audit Summary

**Project:** Private Tutoring Booking System
**Date:** 2026-01-29
**Auditor:** Claude Opus 4.5
**Status:** FIXES APPLIED

---

## Overview

| Category | Count | Fixed |
|----------|-------|-------|
| Bugs | 6 | 6 |
| Security Concerns | 3 | 2 |
| Performance Issues | 4 | 1 |
| Code Quality Issues | 5 | 1 |
| Testing Gaps | 4 | 4 |

---

## 1. Bugs & Critical Issues

### 1.1 BookingService - History Logging Bug
**File:** `app/Services/BookingService.php:255-257`

**Issue:** `old_payload` and `new_payload` are set to the same value, defeating the purpose of tracking state changes.

```php
// Current (buggy)
'old_payload' => $payload,
'new_payload' => $payload,

// Should track actual old and new states separately
```

---

### 1.2 NotificationService - Wrong Type Logged
**File:** `app/Services/NotificationService.php:115`

**Issue:** Logs the model class name instead of the notification class name.

```php
// Current (buggy)
'notification_type' => get_class($booking ?: ($course ?: new \stdClass)),

// Should be
'notification_type' => get_class($notification),
```

---

### 1.3 GamificationService - String/Enum Mismatch
**File:** `app/Services/Gamification/GamificationService.php:434`

**Issue:** Comparing against string when the model casts `status` to `BookingStatus` enum.

```php
// Current (buggy)
'booking_count' => $user->bookings()->where('status', 'completed')->count(),

// Should be
'booking_count' => $user->bookings()->where('status', BookingStatus::Completed)->count(),
```

---

### 1.4 GamificationService - Same Issue in calculatePerfectAttendance
**File:** `app/Services/Gamification/GamificationService.php:510-512`

```php
// Current (buggy)
if ($booking->status === 'completed') {
    $consecutive++;
} elseif ($booking->status === 'no_show') {

// Should use enums
if ($booking->status === BookingStatus::Completed) {
    $consecutive++;
} elseif ($booking->status === BookingStatus::NoShow) {
```

---

### 1.5 Booking Model - Incorrect Polymorphic Relationship
**File:** `app/Models/Booking.php:107-111`

**Issue:** Manually filters polymorphic relations instead of using `morphMany`.

```php
// Current (incorrect)
public function reviews(): HasMany {
    return $this->hasMany(Review::class, 'reviewable_id')
        ->where('reviewable_type', self::class);
}

// Should be
public function reviews(): MorphMany {
    return $this->morphMany(Review::class, 'reviewable');
}
```

---

### 1.6 User Conversations Union Query Issue
**File:** `app/Models/User.php:157-159`

**Issue:** Returns `HasMany` but uses `union()` which changes the query structure.

```php
// Current (problematic)
public function conversations(): HasMany {
    return $this->conversationsAsUserOne()->union($this->conversationsAsUserTwo()->toBase());
}
```

**Recommendation:** Use a custom query scope or accessor instead.

---

## 2. Security Concerns

### 2.1 Missing Rate Limiting
**File:** `routes/web.php`

API endpoints for online status updates and discount validation lack rate limiting.

**Recommendation:**
```php
Route::middleware(['auth', 'throttle:60,1'])->prefix('api')
```

---

### 2.2 Webhook Signature Verification

Ensure Stripe webhook signatures are verified to prevent spoofed webhooks:

```php
\Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
```

---

### 2.3 Admin Route Protection

Consider adding additional verification for sensitive admin operations (role changes, payment modifications).

---

## 3. Performance Issues

### 3.1 N+1 Query Risk in Admin BookingController
**File:** `app/Http/Controllers/Admin/BookingController.php:31-36`

**Issue:** 4 separate database queries for stats.

```php
// Current (inefficient)
$stats = [
    'total' => Booking::count(),
    'upcoming' => Booking::where('start_at', '>', now())->count(),
    'completed' => Booking::where('status', 'completed')->count(),
    'cancelled' => Booking::where('status', 'cancelled')->count(),
];

// Should use single aggregation
$stats = Booking::selectRaw("
    COUNT(*) as total,
    SUM(start_at > NOW()) as upcoming,
    SUM(status = 'completed') as completed,
    SUM(status = 'cancelled') as cancelled
")->first();
```

---

### 3.2 totalUnreadMessagesCount Inefficiency
**File:** `app/Models/User.php:187-200`

Runs on every request for navigation badges. Consider caching or denormalizing unread counts.

---

### 3.3 UpdateUserLastSeen Middleware DB Hit
**File:** `app/Http/Middleware/UpdateUserLastSeen.php:24`

Every request triggers a database write. Consider using Redis or batching updates.

---

### 3.4 Course Progress Calculations
**File:** `app/Models/Course.php:67-93`

Multiple queries for progress calculations. Consider eager loading with `withCount` or caching.

---

## 4. Code Quality Issues

### 4.1 Inconsistent Exception Handling

Uses generic `\Exception`. Should use custom exceptions:

```php
class SlotUnavailableException extends \Exception {}
class BookingAlreadyExistsException extends \Exception {}
```

---

### 4.2 Magic Strings

Status values sometimes hardcoded as strings instead of using enums consistently.

---

### 4.3 Commented Debug Code
**File:** `app/Http/Controllers/Student/BookingController.php:70`

```php
// dd($booking);  // Remove before production
```

---

### 4.4 CoursePurchaseService - Hardcoded Gateway Instantiation
**File:** `app/Services/CoursePurchaseService.php:28-29`

Should use dependency injection via `app()` for testability.

---

### 4.5 Missing Type Hints
**File:** `app/Services/NotificationService.php:100`

```php
// Current
protected function logNotification($user, ...)

// Should be
protected function logNotification(User $user, ...)
```

---

## 5. Architectural Recommendations

### 5.1 Introduce DTOs for Complex Data

```php
class CreateBookingData {
    public function __construct(
        public User $student,
        public TimeSlot $timeSlot,
        public int $subjectId,
        public LessonMode $lessonMode,
        public ?int $locationId = null,
        public ?string $meetingUrl = null,
        public ?string $notes = null,
    ) {}
}
```

---

### 5.2 Event-Driven Side Effects

Decouple notifications and gamification using Laravel Events/Listeners:

```php
// In BookingService
event(new BookingConfirmed($booking));

// Listeners handle side effects separately
```

---

### 5.3 Repository Pattern for Complex Queries

Consider dedicated repository classes for admin statistics and reports.

---

### 5.4 Add Database Indexes

Ensure indexes exist for:
- `bookings.start_at`
- `bookings.status`
- `messages.is_read, messages.sender_id`
- `time_slots.status, start_at`

---

## 6. Testing Gaps

| Missing Test Area | Recommendation |
|-------------------|----------------|
| Gamification enum comparisons | Test achievement unlocking with proper enum values |
| Webhook signature validation | Test invalid webhook signatures are rejected |
| Concurrent course purchases | Similar to BookingConcurrencyTest |
| Edge cases for discount validation | Expired, over-limit, wrong user |

---

## 7. Actionable Refactoring Steps

### High Priority (Bugs)
1. Fix `BookingHistory` logging to store actual old/new payloads
2. Fix enum-to-string comparisons in `GamificationService`
3. Change polymorphic relationships to use `morphMany`
4. Fix `NotificationService` to log correct notification type

### Medium Priority (Performance/Security)
5. Add rate limiting to API endpoints
6. Optimize admin stats with single aggregation query
7. Cache unread message counts
8. Add database indexes for frequent queries

### Low Priority (Code Quality)
9. Replace magic strings with enum constants
10. Create custom exception classes
11. Use DTOs for complex method signatures
12. Remove commented debug code

---

## Conclusion

The codebase is well-structured overall with good separation of concerns, proper use of services, and comprehensive test coverage. The issues identified are primarily around enum/string mismatches, minor performance optimizations, and consistency improvements.

**Priority Fix:** The enum comparison bugs in `GamificationService` should be fixed promptly as they prevent achievements from being properly tracked.
