<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\User;
use App\Services\Gamification\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function index(): View
    {
        $achievements = Achievement::orderBy('type')->orderBy('points')->get();

        return view('admin.achievements.index', compact('achievements'));
    }

    public function create(): View
    {
        $types = [
            'booking_count' => 'Booking Count',
            'course_enrolled' => 'Course Enrolled',
            'course_completed' => 'Course Completed',
            'review_given' => 'Review Given',
            'streak' => 'Activity Streak',
            'perfect_attendance' => 'Perfect Attendance',
            'early_booking' => 'Early Booking',
            'points_reached' => 'Points Reached',
        ];

        return view('admin.achievements.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:achievements,slug'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'points' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:255'],
            'threshold' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Achievement::create($validated);

        notify()->success()
            ->title(__('Achievement Created'))
            ->message(__('The achievement has been created successfully.'))
            ->send();

        return redirect()->route('admin.achievements.index');
    }

    public function edit(Achievement $achievement): View
    {
        $types = [
            'booking_count' => 'Booking Count',
            'course_enrolled' => 'Course Enrolled',
            'course_completed' => 'Course Completed',
            'review_given' => 'Review Given',
            'streak' => 'Activity Streak',
            'perfect_attendance' => 'Perfect Attendance',
            'early_booking' => 'Early Booking',
            'points_reached' => 'Points Reached',
        ];

        return view('admin.achievements.edit', compact('achievement', 'types'));
    }

    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:achievements,slug,'.$achievement->id],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'points' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', 'max:255'],
            'threshold' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $achievement->update($validated);

        notify()->success()
            ->title(__('Achievement Updated'))
            ->message(__('The achievement has been updated successfully.'))
            ->send();

        return redirect()->route('admin.achievements.index');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        // Check if achievement has been unlocked by users
        if ($achievement->userAchievements()->whereNotNull('unlocked_at')->count() > 0) {
            notify()->warning()
                ->title(__('Cannot Delete'))
                ->message(__('This achievement has been unlocked by users and cannot be deleted.'))
                ->send();

            return back();
        }

        $achievement->delete();

        notify()->success()
            ->title(__('Achievement Deleted'))
            ->message(__('The achievement has been deleted successfully.'))
            ->send();

        return redirect()->route('admin.achievements.index');
    }

    public function showUsers(Achievement $achievement): View
    {
        $users = $achievement->userAchievements()
            ->with('user')
            ->latest('unlocked_at')
            ->paginate(20);

        return view('admin.achievements.users', compact('achievement', 'users'));
    }

    public function unlockForm(Achievement $achievement): View
    {
        return view('admin.achievements.unlock', compact('achievement'));
    }

    public function unlock(Request $request, Achievement $achievement): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Check if user already has this achievement unlocked
        $userAchievement = $user->userAchievements()
            ->where('achievement_id', $achievement->id)
            ->first();

        if ($userAchievement && $userAchievement->unlocked_at) {
            notify()->warning()
                ->title(__('Already Unlocked'))
                ->message(__('This user already has this achievement unlocked.'))
                ->send();

            return back();
        }

        if (! $userAchievement) {
            $userAchievement = $user->userAchievements()->create([
                'achievement_id' => $achievement->id,
                'progress' => $achievement->threshold,
            ]);
        }

        $userAchievement->unlock();

        // Award points for the achievement
        $this->gamificationService->awardPoints(
            $user,
            $achievement->points,
            'achievement',
            "Achievement unlocked: {$achievement->name}",
            $achievement
        );

        notify()->success()
            ->title(__('Achievement Unlocked'))
            ->message(__('The achievement has been unlocked for :name.', ['name' => $user->name]))
            ->send();

        return redirect()->route('admin.achievements.users', $achievement);
    }
}
