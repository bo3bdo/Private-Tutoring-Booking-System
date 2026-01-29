<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Gamification\GamificationService;
use Illuminate\View\View;

class GamificationController extends Controller
{
    public function __construct(
        private GamificationService $gamificationService
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        // Get user's gamification data
        $level = $this->gamificationService->getUserLevel($user);
        $achievements = $this->gamificationService->getUserAchievements($user);
        $badges = $this->gamificationService->getUserBadges($user);
        $pointsHistory = $user->pointsHistory()->latest()->limit(20)->get();
        $leaderboard = $this->gamificationService->getLeaderboard(10);
        $userRank = $this->gamificationService->getUserRank($user) ?? ['rank' => 0];

        // Calculate progress stats
        $achievementsCollection = collect($achievements);
        $totalAchievements = $achievementsCollection->count();
        $unlockedAchievements = $achievementsCollection->where('is_unlocked', true)->count();
        $nextLevelPoints = $level['next'] ? $level['next']['min_points'] : $level['current']['min_points'];
        $currentLevelPoints = $level['current']['min_points'];
        $pointsInCurrentLevel = $user->total_points - $currentLevelPoints;
        $pointsNeededForNextLevel = $nextLevelPoints - $currentLevelPoints;
        $progressPercentage = $pointsNeededForNextLevel > 0
            ? min(100, round(($pointsInCurrentLevel / $pointsNeededForNextLevel) * 100))
            : 100;

        return view('student.gamification.index', compact(
            'user',
            'level',
            'achievements',
            'badges',
            'pointsHistory',
            'leaderboard',
            'userRank',
            'totalAchievements',
            'unlockedAchievements',
            'progressPercentage',
            'pointsInCurrentLevel',
            'pointsNeededForNextLevel'
        ));
    }

    public function achievements(): View
    {
        $user = auth()->user();

        $achievements = $this->gamificationService->getUserAchievements($user);
        $badges = $this->gamificationService->getUserBadges($user);
        $level = $this->gamificationService->getUserLevel($user);

        // Group achievements by category
        $groupedAchievements = collect($achievements)->groupBy('achievement.category');

        // Get all available badges from database
        $availableBadges = \App\Models\Badge::where('is_active', true)
            ->orderBy('points_threshold')
            ->get()
            ->toArray();

        return view('student.gamification.achievements', compact(
            'user',
            'achievements',
            'badges',
            'level',
            'groupedAchievements',
            'availableBadges'
        ));
    }

    public function leaderboard(): View
    {
        $user = auth()->user();

        $leaderboard = $this->gamificationService->getLeaderboard(50);
        $userRank = $this->gamificationService->getUserRank($user);
        $level = $this->gamificationService->getUserLevel($user);

        // Get nearby users (above and below current user)
        $nearbyUsers = collect();
        if ($userRank && $userRank['rank'] > 0) {
            $nearbyUsers = collect($this->gamificationService->getLeaderboard(50))
                ->filter(fn ($u) => abs($u['rank'] - $userRank['rank']) <= 2 && $u['id'] !== $user->id);
        }

        return view('student.gamification.leaderboard', compact(
            'user',
            'leaderboard',
            'userRank',
            'level',
            'nearbyUsers'
        ));
    }
}
