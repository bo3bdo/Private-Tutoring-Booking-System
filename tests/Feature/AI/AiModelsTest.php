<?php

use App\Models\AiRecommendation;
use App\Models\StudentLearningInsight;
use App\Models\User;
use App\Models\UserLearningPreference;

it('can create AI recommendation', function () {
    $user = User::factory()->create();

    $recommendation = AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'teacher',
        'recommendation_data' => [
            ['id' => 1, 'score' => 95],
            ['id' => 2, 'score' => 80],
        ],
        'context' => ['reason' => 'Based on your preferences'],
        'algorithm_version' => 'v1.0',
        'generated_at' => now(),
    ]);

    expect($recommendation->type)->toBe('teacher');
    expect($recommendation->recommendation_data)->toHaveCount(2);
    expect($recommendation->algorithm_version)->toBe('v1.0');
});

it('belongs to user', function () {
    $user = User::factory()->create();

    $recommendation = AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'course',
        'recommendation_data' => [],
        'generated_at' => now(),
    ]);

    expect($recommendation->user->id)->toBe($user->id);
});

it('scopes by user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    AiRecommendation::create([
        'user_id' => $user1->id,
        'type' => 'teacher',
        'recommendation_data' => [],
        'generated_at' => now(),
    ]);

    AiRecommendation::create([
        'user_id' => $user2->id,
        'type' => 'course',
        'recommendation_data' => [],
        'generated_at' => now(),
    ]);

    $user1Recs = AiRecommendation::forUser($user1->id)->get();

    expect($user1Recs)->toHaveCount(1);
    expect($user1Recs->first()->user_id)->toBe($user1->id);
});

it('scopes by type', function () {
    $user = User::factory()->create();

    AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'teacher',
        'recommendation_data' => [],
        'generated_at' => now(),
    ]);

    AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'course',
        'recommendation_data' => [],
        'generated_at' => now(),
    ]);

    $teacherRecs = AiRecommendation::ofType('teacher')->get();

    expect($teacherRecs)->toHaveCount(1);
    expect($teacherRecs->first()->type)->toBe('teacher');
});

it('scopes recent recommendations', function () {
    $user = User::factory()->create();

    AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'teacher',
        'recommendation_data' => [],
        'generated_at' => now()->subHours(2),
    ]);

    AiRecommendation::create([
        'user_id' => $user->id,
        'type' => 'course',
        'recommendation_data' => [],
        'generated_at' => now()->subDays(2),
    ]);

    $recent = AiRecommendation::recent(24)->get();

    expect($recent)->toHaveCount(1);
});

it('checks if recommendation is fresh', function () {
    $fresh = AiRecommendation::create([
        'user_id' => User::factory()->create()->id,
        'type' => 'teacher',
        'recommendation_data' => [],
        'generated_at' => now()->subHours(12),
    ]);

    $stale = AiRecommendation::create([
        'user_id' => User::factory()->create()->id,
        'type' => 'course',
        'recommendation_data' => [],
        'generated_at' => now()->subDays(2),
    ]);

    expect($fresh->isFresh())->toBeTrue();
    expect($stale->isFresh())->toBeFalse();
});

describe('User Learning Preference', function () {
    it('can create learning preference', function () {
        $user = User::factory()->create();

        $preference = UserLearningPreference::create([
            'user_id' => $user->id,
            'preferred_subjects' => [1, 2, 3],
            'preferred_times' => ['morning', 'evening'],
            'preferred_lesson_mode' => 'online',
            'learning_goals' => ['Improve math', 'Learn programming'],
            'budget_per_hour' => 50,
            'learning_style' => ['visual', 'hands-on'],
        ]);

        expect($preference->preferred_subjects)->toBe([1, 2, 3]);
        expect($preference->preferred_lesson_mode)->toBe('online');
        expect($preference->budget_per_hour)->toBe(50);
    });

    it('belongs to user', function () {
        $user = User::factory()->create();

        $preference = UserLearningPreference::create([
            'user_id' => $user->id,
            'preferred_lesson_mode' => 'both',
        ]);

        expect($preference->user->id)->toBe($user->id);
    });

    it('scopes by learning style', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        UserLearningPreference::create([
            'user_id' => $user1->id,
            'learning_style' => ['visual', 'auditory'],
        ]);

        UserLearningPreference::create([
            'user_id' => $user2->id,
            'learning_style' => ['kinesthetic'],
        ]);

        $visual = UserLearningPreference::byLearningStyle('visual')->get();

        expect($visual)->toHaveCount(1);
        expect($visual->first()->user_id)->toBe($user1->id);
    });
});

describe('Student Learning Insight', function () {
    it('can create learning insight', function () {
        $user = User::factory()->create();

        $insight = StudentLearningInsight::create([
            'student_id' => $user->id,
            'total_bookings' => 10,
            'completed_lessons' => 8,
            'courses_completed' => 2,
            'average_rating_given' => 4.5,
            'engagement_score' => 75.5,
            'subject_interests' => ['math' => 0.8, 'science' => 0.6],
            'teacher_preferences' => ['experienced' => true],
        ]);

        expect($insight->total_bookings)->toBe(10);
        expect((float) $insight->engagement_score)->toBe(75.5);
        expect($insight->subject_interests)->toHaveKey('math');
    });

    it('belongs to student', function () {
        $user = User::factory()->create();

        $insight = StudentLearningInsight::create([
            'student_id' => $user->id,
            'total_bookings' => 5,
        ]);

        expect($insight->student->id)->toBe($user->id);
    });

    it('scopes high engagement', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        StudentLearningInsight::create([
            'student_id' => $user1->id,
            'engagement_score' => 80,
        ]);

        StudentLearningInsight::create([
            'student_id' => $user2->id,
            'engagement_score' => 50,
        ]);

        $highEngagement = StudentLearningInsight::highEngagement(70)->get();

        expect($highEngagement)->toHaveCount(1);
        expect($highEngagement->first()->student_id)->toBe($user1->id);
    });

    it('scopes needs reanalysis', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        StudentLearningInsight::create([
            'student_id' => $user1->id,
            'last_analyzed_at' => now()->subDays(3),
        ]);

        StudentLearningInsight::create([
            'student_id' => $user2->id,
            'last_analyzed_at' => now()->subHours(2),
        ]);

        $needsReanalysis = StudentLearningInsight::needsReanalysis(24)->get();

        expect($needsReanalysis)->toHaveCount(1);
        expect($needsReanalysis->first()->student_id)->toBe($user1->id);
    });

    it('updates engagement score', function () {
        $user = User::factory()->create();

        $insight = StudentLearningInsight::create([
            'student_id' => $user->id,
            'total_bookings' => 10,
            'courses_completed' => 3,
            'average_rating_given' => 4.0,
        ]);

        $insight->updateEngagementScore();

        expect($insight->fresh()->engagement_score)->toBeGreaterThan(0);
        expect($insight->fresh()->last_analyzed_at)->not->toBeNull();
    });
});
