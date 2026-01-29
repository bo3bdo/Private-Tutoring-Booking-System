<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use App\Services\Gamification\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function index(): View
    {
        $badges = Badge::orderBy('tier')->orderBy('name')->get();

        return view('admin.badges.index', compact('badges'));
    }

    public function create(): View
    {
        $tiers = ['bronze', 'silver', 'gold', 'platinum'];

        return view('admin.badges.create', compact('tiers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:badges,slug'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'tier' => ['required', 'in:bronze,silver,gold,platinum'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Badge::create($validated);

        notify()->success()
            ->title(__('Badge Created'))
            ->message(__('The badge has been created successfully.'))
            ->send();

        return redirect()->route('admin.badges.index');
    }

    public function edit(Badge $badge): View
    {
        $tiers = ['bronze', 'silver', 'gold', 'platinum'];

        return view('admin.badges.edit', compact('badge', 'tiers'));
    }

    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:badges,slug,'.$badge->id],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'tier' => ['required', 'in:bronze,silver,gold,platinum'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $badge->update($validated);

        notify()->success()
            ->title(__('Badge Updated'))
            ->message(__('The badge has been updated successfully.'))
            ->send();

        return redirect()->route('admin.badges.index');
    }

    public function destroy(Badge $badge): RedirectResponse
    {
        // Check if badge has been awarded to users
        if ($badge->userBadges()->count() > 0) {
            notify()->warning()
                ->title(__('Cannot Delete'))
                ->message(__('This badge has been awarded to users and cannot be deleted.'))
                ->send();

            return back();
        }

        $badge->delete();

        notify()->success()
            ->title(__('Badge Deleted'))
            ->message(__('The badge has been deleted successfully.'))
            ->send();

        return redirect()->route('admin.badges.index');
    }

    public function showUsers(Badge $badge): View
    {
        $users = $badge->userBadges()
            ->with('user')
            ->latest('awarded_at')
            ->paginate(20);

        return view('admin.badges.users', compact('badge', 'users'));
    }

    public function awardForm(Badge $badge): View
    {
        return view('admin.badges.award', compact('badge'));
    }

    public function award(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Check if user already has this badge
        if ($user->userBadges()->where('badge_id', $badge->id)->exists()) {
            notify()->warning()
                ->title(__('Already Awarded'))
                ->message(__('This user already has this badge.'))
                ->send();

            return back();
        }

        $user->userBadges()->create([
            'badge_id' => $badge->id,
            'awarded_at' => now(),
        ]);

        notify()->success()
            ->title(__('Badge Awarded'))
            ->message(__('The badge has been awarded to :name.', ['name' => $user->name]))
            ->send();

        return redirect()->route('admin.badges.users', $badge);
    }

    public function revoke(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $userBadge = $badge->userBadges()
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($userBadge) {
            $userBadge->delete();

            notify()->success()
                ->title(__('Badge Revoked'))
                ->message(__('The badge has been revoked from the user.'))
                ->send();
        }

        return back();
    }
}
