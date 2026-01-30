<?php

namespace App\Http\Controllers\Student;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TeacherRequest;
use App\Services\AI\RecommendationEngine;
use App\Services\Gamification\GamificationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        $allBookingsList = $student->bookings()
            ->with(['payment', 'teacher', 'teacher.user', 'subject'])
            ->latest('created_at')
            ->get();

        $totalPaid = $this->calculateTotalPaid($allBookingsList);
        $monthPaid = $this->calculateTotalPaid($allBookingsList, 'month');
        $weekPaid = $this->calculateTotalPaid($allBookingsList, 'week');
        $pendingPayments = $this->calculatePendingPayments($allBookingsList);

        $totalBookings = $allBookingsList->count();
        $upcomingBookings = $allBookingsList
            ->filter(fn ($b) => $b->start_at > now() && $b->status === BookingStatus::Confirmed)
            ->count();
        $completedBookings = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Completed)->count();
        $cancelledBookings = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Cancelled)->count();

        $totalHours = $allBookingsList
            ->filter(fn ($b) => $b->status === BookingStatus::Completed)
            ->sum(fn ($b) => $b->start_at->diffInMinutes($b->end_at) / 60);

        $subjectsStudied = $allBookingsList->pluck('subject_id')->filter()->unique()->count();
        $uniqueTeachers = $allBookingsList->pluck('teacher_id')->filter()->unique()->count();

        $subjectGroups = $allBookingsList->groupBy('subject_id')->map->count()->sortDesc();
        $mostStudiedSubjectId = $subjectGroups->keys()->first();
        $mostStudiedSubjectName = 'N/A';
        if ($mostStudiedSubjectId) {
            $booking = $allBookingsList->first(fn ($b) => $b->subject_id == $mostStudiedSubjectId);
            $mostStudiedSubjectName = $booking?->subject?->name ?? 'N/A';
        }

        $newBookingsThisMonth = $allBookingsList
            ->filter(fn ($b) => $b->created_at->month === now()->month && $b->created_at->year === now()->year)
            ->count();

        $completedBookingsList = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Completed);
        $averageDuration = $completedBookingsList->count() > 0
            ? $completedBookingsList->avg(fn ($b) => $b->start_at->diffInMinutes($b->end_at))
            : 0;

        $upcomingBookingsList = $student->bookings()
            ->where('start_at', '>', now())
            ->where('status', BookingStatus::Confirmed->value)
            ->with(['teacher.user', 'subject'])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        // Recent bookings
        $recentBookings = $student->bookings()
            ->with(['teacher.user', 'subject'])
            ->latest('start_at')
            ->limit(10)
            ->get();

        // Check for completed bookings without reviews and show notifications
        $this->checkAndNotifyUnreviewedBookings($student);

        // Check if user has a teacher request
        $teacherRequest = TeacherRequest::where('user_id', $student->id)
            ->latest('created_at')
            ->first();

        // Gamification data
        $gamificationService = app(GamificationService::class);
        $userLevel = $gamificationService->getUserLevel($student);
        $userRank = $gamificationService->getUserRank($student) ?? ['rank' => 0];
        $achievements = $gamificationService->getUserAchievements($student);
        $unlockedAchievements = collect($achievements)->where('is_unlocked', true)->count();

        // AI Recommendations
        $recommendationEngine = app(RecommendationEngine::class);
        $recommendedTeachers = $recommendationEngine->recommendTeachers($student, 3);
        $recommendedCourses = $recommendationEngine->recommendCourses($student, 3);
        $engagementAnalysis = $recommendationEngine->analyzeEngagement($student);

        return view('student.dashboard', compact(
            'totalPaid',
            'monthPaid',
            'weekPaid',
            'pendingPayments',
            'totalBookings',
            'upcomingBookings',
            'completedBookings',
            'cancelledBookings',
            'totalHours',
            'subjectsStudied',
            'uniqueTeachers',
            'mostStudiedSubjectName',
            'newBookingsThisMonth',
            'averageDuration',
            'upcomingBookingsList',
            'recentBookings',
            'teacherRequest',
            'userLevel',
            'userRank',
            'unlockedAchievements',
            'recommendedTeachers',
            'recommendedCourses',
            'engagementAnalysis'
        ));
    }

    private function calculateTotalPaid($bookings, string $period = 'all'): float
    {
        $filteredBookings = $bookings->filter(function ($booking) use ($period) {
            if (! $booking->payment || $booking->payment->status !== PaymentStatus::Succeeded) {
                return false;
            }

            $paidAt = $booking->payment->paid_at ?? $booking->created_at;

            return match ($period) {
                'week' => $paidAt->isCurrentWeek(),
                'month' => $paidAt->isCurrentMonth(),
                'all' => true,
                default => false,
            };
        });

        return $filteredBookings->sum(fn ($b) => $b->payment->amount ?? 0);
    }

    private function calculatePendingPayments($bookings): float
    {
        $total = 0;

        foreach ($bookings as $booking) {
            // Skip cancelled bookings
            if ($booking->status === BookingStatus::Cancelled) {
                continue;
            }

            // If booking has a payment record with pending/initiated status
            if ($booking->payment && in_array($booking->payment->status, [PaymentStatus::Pending, PaymentStatus::Initiated])) {
                $total += $booking->payment->amount ?? 0;
            }
            // If booking is awaiting payment but has no payment record yet
            elseif ($booking->status === BookingStatus::AwaitingPayment && ! $booking->payment) {
                // Calculate amount from teacher's hourly rate
                $hourlyRate = $booking->teacher->hourly_rate ?? 25.00;
                $duration = $booking->start_at->diffInMinutes($booking->end_at) / 60;
                $total += $hourlyRate * $duration;
            }
        }

        return $total;
    }

    private function checkAndNotifyUnreviewedBookings($student): void
    {
        // Get count of completed bookings that don't have reviews from this student
        $unreviewedCount = Booking::where('student_id', $student->id)
            ->where('status', BookingStatus::Completed->value)
            ->whereDoesntHave('reviews', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })
            ->count();

        if ($unreviewedCount === 0) {
            return;
        }

        // Show a single consolidated notification
        notify()
            ->info()
            ->title(__('common.Rate Your Lessons'))
            ->message(trans_choice('common.You have :count completed lesson that needs your review|You have :count completed lessons that need your review', $unreviewedCount, [
                'count' => $unreviewedCount,
            ]))
            ->duration(8000)
            ->actions([
                \Mckenziearts\Notify\Action\NotifyAction::make()
                    ->label(__('common.View Bookings'))
                    ->url(route('student.bookings.index', ['filter' => 'past'])),
            ])
            ->send();
    }
}
