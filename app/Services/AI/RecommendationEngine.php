<?php

namespace App\Services\AI;

use App\Models\AiRecommendation;
use App\Models\Booking;
use App\Models\Course;
use App\Models\StudentLearningInsight;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\UserLearningPreference;
use Illuminate\Support\Facades\Log;

/**
 * AI Recommendation Engine
 * Provides smart recommendations for teachers, courses, and time slots
 */
class RecommendationEngine
{
    /**
     * Get personalized teacher recommendations for a student
     */
    public function recommendTeachers(User $student, int $limit = 5): array
    {
        $insight = $this->getOrCreateInsight($student);
        $preferences = $this->getOrCreatePreferences($student);

        // Get teachers based on multiple factors
        $teachers = TeacherProfile::with(['user', 'subjects', 'reviews'])
            ->where('is_active', true)
            ->get();

        $scoredTeachers = [];
        foreach ($teachers as $teacher) {
            $score = $this->calculateTeacherScore($teacher, $student, $insight, $preferences);
            $scoredTeachers[] = [
                'teacher' => $teacher,
                'score' => $score,
                'reasons' => $this->getTeacherRecommendationReasons($teacher, $preferences),
            ];
        }

        // Sort by score descending
        usort($scoredTeachers, fn ($a, $b) => $b['score'] <=> $a['score']);

        $recommendations = array_slice($scoredTeachers, 0, $limit);

        // Store recommendations
        $this->storeRecommendations($student, 'teacher', $recommendations);

        return $recommendations;
    }

    /**
     * Get course recommendations for a student
     */
    public function recommendCourses(User $student, int $limit = 5): array
    {
        $insight = $this->getOrCreateInsight($student);
        $preferences = $this->getOrCreatePreferences($student);

        $courses = Course::with(['teacher', 'subject'])
            ->where('is_published', true)
            ->whereDoesntHave('enrollments', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->get();

        $scoredCourses = [];
        foreach ($courses as $course) {
            $score = $this->calculateCourseScore($course, $student, $insight, $preferences);
            $scoredCourses[] = [
                'course' => $course,
                'score' => $score,
                'reasons' => $this->getCourseRecommendationReasons($course, $preferences),
            ];
        }

        usort($scoredCourses, fn ($a, $b) => $b['score'] <=> $a['score']);

        $recommendations = array_slice($scoredCourses, 0, $limit);

        $this->storeRecommendations($student, 'course', $recommendations);

        return $recommendations;
    }

    /**
     * Recommend optimal time slots based on student's history
     */
    public function recommendTimeSlots(User $student, TeacherProfile $teacher, int $limit = 5): array
    {
        $insight = $this->getOrCreateInsight($student);
        $preferences = $this->getOrCreatePreferences($student);

        // Get student's preferred times
        $preferredTimes = $preferences->preferred_times ?? [];

        // Get student's booking history
        $pastBookings = Booking::where('student_id', $student->id)
            ->where('start_at', '<', now())
            ->orderBy('start_at', 'desc')
            ->take(20)
            ->get();

        // Analyze patterns
        $preferredDays = $this->analyzePreferredDays($pastBookings);
        $preferredHours = $this->analyzePreferredHours($pastBookings);

        // Get available slots from teacher
        $availableSlots = $teacher->timeSlots()
            ->where('status', 'available')
            ->where('start_at', '>', now()->addHour())
            ->where('start_at', '<', now()->addWeeks(2))
            ->get();

        $scoredSlots = [];
        foreach ($availableSlots as $slot) {
            $score = $this->calculateTimeSlotScore($slot, $preferredDays, $preferredHours, $preferredTimes);
            $scoredSlots[] = [
                'slot' => $slot,
                'score' => $score,
                'reason' => $this->getTimeSlotRecommendationReason($slot, $preferredDays, $preferredHours),
            ];
        }

        usort($scoredSlots, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($scoredSlots, 0, $limit);
    }

    /**
     * Get learning path suggestions based on student's goals
     */
    public function suggestLearningPath(User $student): array
    {
        $insight = $this->getOrCreateInsight($student);
        $preferences = $this->getOrCreatePreferences($student);

        $suggestions = [];
        $subjects = Subject::whereIn('id', $preferences->preferred_subjects ?? [])->get();

        foreach ($subjects as $subject) {
            $progress = $this->calculateSubjectProgress($student, $subject);
            $nextSteps = $this->suggestNextSteps($student, $subject, $progress);

            $suggestions[] = [
                'subject' => $subject,
                'progress' => $progress,
                'next_steps' => $nextSteps,
                'estimated_completion' => $this->estimateCompletionTime($student, $subject, $progress),
            ];
        }

        return $suggestions;
    }

    /**
     * Analyze student's engagement and provide insights
     */
    public function analyzeEngagement(User $student): array
    {
        $insight = $this->getOrCreateInsight($student);

        // Get recent activity
        $recentBookings = Booking::where('student_id', $student->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $completedBookings = Booking::where('student_id', $student->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(30))
            ->count();

        $completionRate = $recentBookings > 0
            ? round(($completedBookings / $recentBookings) * 100, 2)
            : 0;

        // Calculate trend
        $previousMonthBookings = Booking::where('student_id', $student->id)
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->count();

        $trend = $previousMonthBookings > 0
            ? round((($recentBookings - $previousMonthBookings) / $previousMonthBookings) * 100, 2)
            : 0;

        return [
            'engagement_score' => $insight->engagement_score,
            'completion_rate' => $completionRate,
            'monthly_bookings' => $recentBookings,
            'trend_percentage' => $trend,
            'trend_direction' => $trend > 0 ? 'up' : ($trend < 0 ? 'down' : 'stable'),
            'recommendations' => $this->generateEngagementRecommendations($insight, $completionRate),
        ];
    }

    private function getOrCreateInsight(User $student): StudentLearningInsight
    {
        return StudentLearningInsight::firstOrCreate(
            ['student_id' => $student->id],
            [
                'total_bookings' => 0,
                'completed_lessons' => 0,
                'courses_completed' => 0,
                'engagement_score' => 0,
            ]
        );
    }

    private function getOrCreatePreferences(User $student): UserLearningPreference
    {
        return UserLearningPreference::firstOrCreate(
            ['user_id' => $student->id],
            [
                'preferred_lesson_mode' => 'both',
            ]
        );
    }

    private function calculateTeacherScore(TeacherProfile $teacher, User $student, StudentLearningInsight $insight, UserLearningPreference $preferences): float
    {
        $score = 50; // Base score

        // Subject match
        $preferredSubjects = $preferences->preferred_subjects ?? [];
        $teacherSubjects = $teacher->subjects->pluck('id')->toArray();
        $subjectOverlap = count(array_intersect($preferredSubjects, $teacherSubjects));
        $score += $subjectOverlap * 10;

        // Rating (max 20 points)
        $rating = $teacher->average_rating ?? 0;
        $score += ($rating / 5) * 20;

        // Experience (max 10 points)
        $experienceYears = $teacher->experience_years ?? 0;
        $score += min($experienceYears * 2, 10);

        // Price match (if budget is set)
        if ($preferences->budget_per_hour && $teacher->hourly_rate) {
            if ($teacher->hourly_rate <= $preferences->budget_per_hour) {
                $score += 10;
            } elseif ($teacher->hourly_rate <= $preferences->budget_per_hour * 1.2) {
                $score += 5;
            }
        }

        // Availability bonus
        $upcomingSlots = $teacher->timeSlots()
            ->where('status', 'available')
            ->where('start_at', '>', now())
            ->where('start_at', '<', now()->addWeek())
            ->count();
        $score += min($upcomingSlots * 0.5, 10);

        return min($score, 100);
    }

    private function calculateCourseScore(Course $course, User $student, StudentLearningInsight $insight, UserLearningPreference $preferences): float
    {
        $score = 50;

        // Subject match
        if (in_array($course->subject_id, $preferences->preferred_subjects ?? [])) {
            $score += 20;
        }

        // Price match
        if ($preferences->budget_per_hour && $course->price) {
            $hourlyEquivalent = $course->price / ($course->duration_hours ?? 1);
            if ($hourlyEquivalent <= $preferences->budget_per_hour) {
                $score += 15;
            }
        }

        // Rating
        $score += ($course->average_rating ?? 3) * 3;

        // Popularity
        $enrollments = $course->enrollments()->count();
        $score += min($enrollments * 0.5, 10);

        return min($score, 100);
    }

    private function calculateTimeSlotScore($slot, array $preferredDays, array $preferredHours, array $preferredTimes): float
    {
        $score = 50;
        $dayOfWeek = $slot->start_at->format('l');
        $hour = (int) $slot->start_at->format('H');

        // Preferred day bonus
        if (isset($preferredDays[$dayOfWeek])) {
            $score += 20;
        }

        // Preferred hour bonus
        if (isset($preferredHours[$hour])) {
            $score += 20;
        }

        // Time proximity (prefer slots in the next few days)
        $daysFromNow = $slot->start_at->diffInDays(now());
        if ($daysFromNow <= 3) {
            $score += 10;
        }

        return min($score, 100);
    }

    private function analyzePreferredDays($bookings): array
    {
        $days = [];
        foreach ($bookings as $booking) {
            $day = $booking->start_at->format('l');
            $days[$day] = ($days[$day] ?? 0) + 1;
        }
        arsort($days);

        return array_slice($days, 0, 3, true);
    }

    private function analyzePreferredHours($bookings): array
    {
        $hours = [];
        foreach ($bookings as $booking) {
            $hour = (int) $booking->start_at->format('H');
            $hours[$hour] = ($hours[$hour] ?? 0) + 1;
        }
        arsort($hours);

        return array_slice($hours, 0, 3, true);
    }

    private function getTeacherRecommendationReasons(TeacherProfile $teacher, UserLearningPreference $preferences): array
    {
        $reasons = [];

        $preferredSubjects = $preferences->preferred_subjects ?? [];
        $teacherSubjects = $teacher->subjects->pluck('id')->toArray();
        $matchingSubjects = array_intersect($preferredSubjects, $teacherSubjects);

        if (! empty($matchingSubjects)) {
            $reasons[] = 'Teaches your preferred subjects';
        }

        if ($teacher->average_rating >= 4.5) {
            $reasons[] = 'Highly rated by students';
        }

        if ($teacher->experience_years >= 5) {
            $reasons[] = 'Highly experienced';
        }

        return $reasons;
    }

    private function getCourseRecommendationReasons(Course $course, UserLearningPreference $preferences): array
    {
        $reasons = [];

        if (in_array($course->subject_id, $preferences->preferred_subjects ?? [])) {
            $reasons[] = 'Matches your interests';
        }

        if ($course->average_rating >= 4.5) {
            $reasons[] = 'Top rated course';
        }

        if ($course->enrollments()->count() > 50) {
            $reasons[] = 'Popular among students';
        }

        return $reasons;
    }

    private function getTimeSlotRecommendationReason($slot, array $preferredDays, array $preferredHours): string
    {
        $day = $slot->start_at->format('l');
        $hour = (int) $slot->start_at->format('H');

        if (isset($preferredDays[$day])) {
            return 'Matches your preferred day';
        }

        if (isset($preferredHours[$hour])) {
            return 'Matches your preferred time';
        }

        return 'Available soon';
    }

    private function calculateSubjectProgress(User $student, Subject $subject): float
    {
        $totalBookings = Booking::where('student_id', $student->id)
            ->whereHas('subject', function ($q) use ($subject) {
                $q->where('id', $subject->id);
            })
            ->count();

        $completedBookings = Booking::where('student_id', $student->id)
            ->where('status', 'completed')
            ->whereHas('subject', function ($q) use ($subject) {
                $q->where('id', $subject->id);
            })
            ->count();

        return $totalBookings > 0 ? ($completedBookings / $totalBookings) * 100 : 0;
    }

    private function suggestNextSteps(User $student, Subject $subject, float $progress): array
    {
        $steps = [];

        if ($progress < 25) {
            $steps[] = 'Book an introductory session';
            $steps[] = 'Set learning goals with your teacher';
        } elseif ($progress < 50) {
            $steps[] = 'Complete more practice exercises';
            $steps[] = 'Consider group study sessions';
        } elseif ($progress < 75) {
            $steps[] = 'Work on advanced topics';
            $steps[] = 'Take assessment tests';
        } else {
            $steps[] = 'Prepare for final assessment';
            $steps[] = 'Review and consolidate learning';
        }

        return $steps;
    }

    private function estimateCompletionTime(User $student, Subject $subject, float $progress): ?string
    {
        if ($progress >= 100) {
            return 'Completed';
        }

        $recentBookings = Booking::where('student_id', $student->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subMonths(3))
            ->count();

        if ($recentBookings === 0) {
            return null;
        }

        $remainingProgress = 100 - $progress;
        $bookingsNeeded = ceil($remainingProgress / 10); // Assume ~10% progress per booking
        $weeksNeeded = ceil($bookingsNeeded / max($recentBookings / 12, 1));

        return "Approximately {$weeksNeeded} weeks";
    }

    private function generateEngagementRecommendations(StudentLearningInsight $insight, float $completionRate): array
    {
        $recommendations = [];

        if ($completionRate < 70) {
            $recommendations[] = 'Try to complete more of your scheduled lessons';
        }

        if ($insight->engagement_score < 50) {
            $recommendations[] = 'Consider booking more regular sessions';
        }

        if ($insight->courses_completed === 0) {
            $recommendations[] = 'Explore our available courses to supplement your learning';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Great job! Keep up the good work';
        }

        return $recommendations;
    }

    private function storeRecommendations(User $student, string $type, array $recommendations): void
    {
        try {
            AiRecommendation::create([
                'user_id' => $student->id,
                'type' => $type,
                'recommendation_data' => array_map(fn ($r) => [
                    'id' => $r[$type]['id'] ?? null,
                    'score' => $r['score'],
                ], $recommendations),
                'context' => [
                    'algorithm_version' => 'v1.0',
                    'total_considered' => count($recommendations) * 3,
                ],
                'algorithm_version' => 'v1.0',
                'generated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store AI recommendations', [
                'user_id' => $student->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
