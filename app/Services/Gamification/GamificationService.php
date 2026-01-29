<?php

namespace App\Services\Gamification;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\LeaderboardEntry;
use App\Models\PointsHistory;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserBadge;
use Illuminate\Support\Facades\DB;

/**
 * Gamification Service
 * Handles points, achievements, badges, and leaderboards
 */
class GamificationService
{
    /**
     * Award points to a user
     */
    public function awardPoints(User $user, int $points, string $source, string $description, $pointable = null): PointsHistory
    {
        return DB::transaction(function () use ($user, $points, $source, $description, $pointable) {
            // Create history record
            $history = PointsHistory::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earned',
                'source' => $source,
                'description' => $description,
                'pointable_type' => $pointable ? get_class($pointable) : null,
                'pointable_id' => $pointable ? $pointable->id : null,
            ]);

            // Update user's total points
            $user->increment('total_points', $points);

            // Update leaderboard
            $this->updateLeaderboard($user, $points);

            // Check for new achievements (skip if source is achievement to prevent recursion)
            if ($source !== 'achievement') {
                $this->checkAchievements($user);
            }

            // Check for badge awards based on new point total
            $this->checkBadgeAwards($user);

            return $history;
        });
    }

    /**
     * Spend points (for rewards, discounts, etc.)
     */
    public function spendPoints(User $user, int $points, string $description): ?PointsHistory
    {
        if ($user->total_points < $points) {
            return null;
        }

        return DB::transaction(function () use ($user, $points, $description) {
            $history = PointsHistory::create([
                'user_id' => $user->id,
                'points' => -$points,
                'type' => 'spent',
                'source' => 'reward_redemption',
                'description' => $description,
            ]);

            $user->decrement('total_points', $points);

            return $history;
        });
    }

    /**
     * Award bonus points
     */
    public function awardBonus(User $user, int $points, string $reason): PointsHistory
    {
        return $this->awardPoints($user, $points, 'bonus', "Bonus: {$reason}");
    }

    /**
     * Track user activity and update streak
     */
    public function trackActivity(User $user): void
    {
        $user->refresh();
        $today = now()->toDateString();

        if ($user->last_activity_date === null) {
            // First activity
            $user->current_streak = 1;
            $user->last_activity_date = $today;
            $user->save();
        } elseif ($user->last_activity_date === $today) {
            // Already active today - do nothing
            return;
        } elseif ($user->last_activity_date === now()->subDay()->toDateString()) {
            // Consecutive day - increase streak
            $newStreak = $user->current_streak + 1;
            $user->current_streak = $newStreak;
            $user->last_activity_date = $today;
            $user->save();

            // Award streak bonus
            if ($newStreak % 7 === 0) {
                $this->awardBonus($user, 50, "{$newStreak} day streak!");
            }
        } else {
            // Streak broken
            $user->current_streak = 1;
            $user->last_activity_date = $today;
            $user->save();
        }
    }

    /**
     * Check and unlock achievements for a user
     */
    public function checkAchievements(User $user): array
    {
        $unlocked = [];
        $achievements = Achievement::active()->get();

        foreach ($achievements as $achievement) {
            $userAchievement = UserAchievement::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                ],
                ['progress' => 0]
            );

            if ($userAchievement->isUnlocked()) {
                continue;
            }

            $progress = $this->calculateAchievementProgress($user, $achievement);
            $userAchievement->progress = $progress;
            $userAchievement->save();

            if ($progress >= $achievement->threshold) {
                $userAchievement->unlock();
                $unlocked[] = $achievement;

                // Award points for achievement
                $this->awardPoints(
                    $user,
                    $achievement->points,
                    'achievement',
                    "Achievement unlocked: {$achievement->name}",
                    $achievement
                );

                // Check for badge awards
                $this->checkBadgeAwards($user);
            }
        }

        return $unlocked;
    }

    /**
     * Get user's achievements with progress
     */
    public function getUserAchievements(User $user): array
    {
        return UserAchievement::with('achievement')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($ua) {
                return [
                    'id' => $ua->achievement->id,
                    'name' => $ua->achievement->name,
                    'description' => $ua->achievement->description,
                    'icon' => $ua->achievement->icon,
                    'color' => $ua->achievement->color,
                    'points' => $ua->achievement->points,
                    'type' => $ua->achievement->type,
                    'category' => $ua->achievement->type,
                    'threshold' => $ua->achievement->threshold,
                    'achievement' => $ua->achievement,
                    'progress' => $ua->progress,
                    'percentage' => min(100, round(($ua->progress / $ua->achievement->threshold) * 100)),
                    'unlocked_at' => $ua->unlocked_at,
                    'is_unlocked' => $ua->isUnlocked(),
                ];
            })
            ->toArray();
    }

    /**
     * Get user's badges
     */
    public function getUserBadges(User $user): array
    {
        return UserBadge::with('badge')
            ->where('user_id', $user->id)
            ->orderByDesc('awarded_at')
            ->get()
            ->map(function ($ub) {
                return [
                    'id' => $ub->badge->id,
                    'name' => $ub->badge->name,
                    'slug' => $ub->badge->slug,
                    'description' => $ub->badge->description,
                    'icon' => $ub->badge->icon,
                    'color' => $ub->badge->color,
                    'tier' => $ub->badge->tier,
                    'points_threshold' => $ub->badge->points_threshold,
                    'awarded_at' => $ub->awarded_at,
                ];
            })
            ->toArray();
    }

    /**
     * Get leaderboard for current month
     */
    public function getLeaderboard(int $limit = 10): array
    {
        return LeaderboardEntry::with('user')
            ->currentMonth()
            ->orderByDesc('points')
            ->limit($limit)
            ->get()
            ->map(function ($entry, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $entry->user->id,
                    'name' => $entry->user->name,
                    'points' => $entry->points,
                    'bookings_count' => $entry->bookings_count,
                    'courses_completed' => $entry->courses_completed,
                ];
            })
            ->toArray();
    }

    /**
     * Get user's rank on leaderboard
     */
    public function getUserRank(User $user): ?array
    {
        $entry = LeaderboardEntry::currentMonth()
            ->where('user_id', $user->id)
            ->first();

        if (! $entry) {
            return null;
        }

        $rank = LeaderboardEntry::currentMonth()
            ->where('points', '>', $entry->points)
            ->count() + 1;

        return [
            'rank' => $rank,
            'points' => $entry->points,
            'bookings_count' => $entry->bookings_count,
            'courses_completed' => $entry->courses_completed,
        ];
    }

    /**
     * Initialize default achievements
     */
    public function seedAchievements(): void
    {
        $achievements = [
            [
                'name' => 'First Steps',
                'slug' => 'first-steps',
                'description' => 'Complete your first booking',
                'icon' => 'footprints',
                'color' => '#10B981',
                'points' => 50,
                'type' => 'booking_count',
                'threshold' => 1,
            ],
            [
                'name' => 'Dedicated Student',
                'slug' => 'dedicated-student',
                'description' => 'Complete 10 bookings',
                'icon' => 'book-open',
                'color' => '#3B82F6',
                'points' => 200,
                'type' => 'booking_count',
                'threshold' => 10,
            ],
            [
                'name' => 'Learning Master',
                'slug' => 'learning-master',
                'description' => 'Complete 50 bookings',
                'icon' => 'graduation-cap',
                'color' => '#8B5CF6',
                'points' => 1000,
                'type' => 'booking_count',
                'threshold' => 50,
            ],
            [
                'name' => 'Course Explorer',
                'slug' => 'course-explorer',
                'description' => 'Enroll in your first course',
                'icon' => 'compass',
                'color' => '#F59E0B',
                'points' => 100,
                'type' => 'course_enrolled',
                'threshold' => 1,
            ],
            [
                'name' => 'Course Graduate',
                'slug' => 'course-graduate',
                'description' => 'Complete 5 courses',
                'icon' => 'award',
                'color' => '#EC4899',
                'points' => 500,
                'type' => 'course_completed',
                'threshold' => 5,
            ],
            [
                'name' => 'Helpful Reviewer',
                'slug' => 'helpful-reviewer',
                'description' => 'Leave 5 reviews',
                'icon' => 'message-square',
                'color' => '#14B8A6',
                'points' => 150,
                'type' => 'review_given',
                'threshold' => 5,
            ],
            [
                'name' => '7-Day Streak',
                'slug' => 'seven-day-streak',
                'description' => 'Maintain a 7-day activity streak',
                'icon' => 'flame',
                'color' => '#EF4444',
                'points' => 100,
                'type' => 'streak',
                'threshold' => 7,
            ],
            [
                'name' => '30-Day Streak',
                'slug' => 'thirty-day-streak',
                'description' => 'Maintain a 30-day activity streak',
                'icon' => 'fire',
                'color' => '#DC2626',
                'points' => 500,
                'type' => 'streak',
                'threshold' => 30,
            ],
            [
                'name' => 'Perfect Attendance',
                'slug' => 'perfect-attendance',
                'description' => 'Complete 10 bookings without any no-shows',
                'icon' => 'calendar-check',
                'color' => '#059669',
                'points' => 300,
                'type' => 'perfect_attendance',
                'threshold' => 10,
            ],
            [
                'name' => 'Early Bird',
                'slug' => 'early-bird',
                'description' => 'Book 5 sessions before 9 AM',
                'icon' => 'sunrise',
                'color' => '#F97316',
                'points' => 150,
                'type' => 'early_booking',
                'threshold' => 5,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(['slug' => $achievement['slug']], $achievement);
        }
    }

    /**
     * Initialize default badges
     */
    public function seedBadges(): void
    {
        $badges = [
            [
                'name' => 'Bronze Learner',
                'slug' => 'bronze-learner',
                'description' => 'Reached 100 points',
                'icon' => 'medal',
                'color' => '#CD7F32',
                'tier' => 'bronze',
            ],
            [
                'name' => 'Silver Scholar',
                'slug' => 'silver-scholar',
                'description' => 'Reached 500 points',
                'icon' => 'medal',
                'color' => '#C0C0C0',
                'tier' => 'silver',
            ],
            [
                'name' => 'Gold Genius',
                'slug' => 'gold-genius',
                'description' => 'Reached 2000 points',
                'icon' => 'medal',
                'color' => '#FFD700',
                'tier' => 'gold',
            ],
            [
                'name' => 'Platinum Prodigy',
                'slug' => 'platinum-prodigy',
                'description' => 'Reached 5000 points',
                'icon' => 'crown',
                'color' => '#E5E4E2',
                'tier' => 'platinum',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['slug' => $badge['slug']], $badge);
        }
    }

    /**
     * Calculate achievement progress
     */
    private function calculateAchievementProgress(User $user, Achievement $achievement): int
    {
        $progress = match ($achievement->type) {
            'booking_count' => $user->bookings()->where('status', 'completed')->count(),
            'course_enrolled' => $user->courseEnrollments()->count(),
            'course_completed' => $user->courseEnrollments()->where('is_completed', true)->count(),
            'review_given' => $user->reviews()->count(),
            'streak' => $user->current_streak,
            'perfect_attendance' => $this->calculatePerfectAttendance($user),
            'early_booking' => $this->calculateEarlyBookings($user),
            default => 0,
        };

        return $progress ?? 0;
    }

    /**
     * Check and award badges
     */
    public function checkBadgeAwards(User $user): void
    {
        $user->refresh();
        $points = $user->total_points;

        $badgeThresholds = [
            'bronze' => 100,
            'silver' => 500,
            'gold' => 2000,
            'platinum' => 5000,
        ];

        foreach ($badgeThresholds as $tier => $threshold) {
            if ($points >= $threshold) {
                $badge = Badge::where('tier', $tier)->first();
                if ($badge) {
                    UserBadge::firstOrCreate([
                        'user_id' => $user->id,
                        'badge_id' => $badge->id,
                    ], [
                        'awarded_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Update leaderboard entry
     */
    private function updateLeaderboard(User $user, int $points): void
    {
        $entry = LeaderboardEntry::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => now()->year,
                'month' => now()->month,
            ],
            [
                'points' => 0,
                'bookings_count' => 0,
                'courses_completed' => 0,
            ]
        );

        $entry->increment('points', $points);
    }

    /**
     * Calculate perfect attendance streak
     */
    private function calculatePerfectAttendance(User $user): int
    {
        $recentBookings = $user->bookings()
            ->orderBy('start_at', 'desc')
            ->take(20)
            ->get();

        $consecutive = 0;
        foreach ($recentBookings as $booking) {
            if ($booking->status === 'completed') {
                $consecutive++;
            } elseif ($booking->status === 'no_show') {
                break;
            }
        }

        return $consecutive;
    }

    /**
     * Calculate early morning bookings
     */
    private function calculateEarlyBookings(User $user): int
    {
        return $user->bookings()
            ->where('status', 'completed')
            ->whereTime('start_at', '<', '09:00:00')
            ->count();
    }

    /**
     * Get user's level based on points
     */
    public function getUserLevel(User $user): array
    {
        $points = $user->total_points;

        $levels = [
            1 => ['name' => 'Beginner', 'min_points' => 0, 'color' => '#6B7280'],
            2 => ['name' => 'Novice', 'min_points' => 100, 'color' => '#10B981'],
            3 => ['name' => 'Intermediate', 'min_points' => 300, 'color' => '#3B82F6'],
            4 => ['name' => 'Advanced', 'min_points' => 700, 'color' => '#8B5CF6'],
            5 => ['name' => 'Expert', 'min_points' => 1500, 'color' => '#F59E0B'],
            6 => ['name' => 'Master', 'min_points' => 3000, 'color' => '#EF4444'],
            7 => ['name' => 'Legend', 'min_points' => 5000, 'color' => '#EC4899'],
        ];

        $currentLevel = $levels[1];
        $nextLevel = $levels[2];

        foreach ($levels as $level => $data) {
            if ($points >= $data['min_points']) {
                $currentLevel = array_merge(['level' => $level], $data);
                $nextLevel = $levels[$level + 1] ?? null;
            }
        }

        $progressToNext = 0;
        if ($nextLevel) {
            $range = $nextLevel['min_points'] - $currentLevel['min_points'];
            $progress = $points - $currentLevel['min_points'];
            $progressToNext = (int) min(100, round(($progress / $range) * 100));
        }

        return [
            'current' => $currentLevel,
            'next' => $nextLevel ? array_merge(['level' => array_search($nextLevel, $levels, true)], $nextLevel) : null,
            'progress_percentage' => $progressToNext,
            'points_to_next' => $nextLevel ? $nextLevel['min_points'] - $points : 0,
        ];
    }

    /**
     * Get comprehensive stats for a user
     */
    public function getUserStats(User $user): array
    {
        $level = $this->getUserLevel($user);
        $achievements = $this->getUserAchievements($user);
        $badges = $this->getUserBadges($user);
        $rank = $this->getUserRank($user);

        return [
            'total_points' => $user->total_points,
            'current_streak' => $user->current_streak,
            'level' => $level,
            'achievements' => [
                'total' => count($achievements),
                'unlocked' => count(array_filter($achievements, fn ($a) => $a['is_unlocked'])),
                'list' => $achievements,
            ],
            'badges' => [
                'total' => count($badges),
                'list' => $badges,
            ],
            'leaderboard' => $rank,
        ];
    }
}
