<?php

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\StudentLearningInsight;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserLearningPreference;
use App\Services\AI\RecommendationEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->engine = new RecommendationEngine;
    $this->student = User::factory()->create();
});

describe('Teacher Recommendations', function () {
    beforeEach(function () {
        $this->subject = Subject::factory()->create(['is_active' => true]);
        $this->teacherUser = User::factory()->create();
        $this->teacher = TeacherProfile::factory()->create([
            'user_id' => $this->teacherUser->id,
            'is_active' => true,
            'hourly_rate' => 25,
        ]);
        $this->teacher->subjects()->attach($this->subject);
    });

    it('recommends teachers based on subject match', function () {
        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$this->subject->id],
        ]);

        $recommendations = $this->engine->recommendTeachers($this->student, 5);

        expect($recommendations)->toHaveCount(1);
        expect($recommendations[0]['teacher']->id)->toBe($this->teacher->id);
        expect($recommendations[0]['score'])->toBeGreaterThan(0);
    });

    it('provides recommendation reasons', function () {
        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$this->subject->id],
        ]);

        $recommendations = $this->engine->recommendTeachers($this->student, 5);

        expect($recommendations[0]['reasons'])->toBeArray();
        expect($recommendations[0]['reasons'])->toContain('Teaches your preferred subjects');
    });
});

describe('Course Recommendations', function () {
    beforeEach(function () {
        $this->subject = Subject::factory()->create(['is_active' => true]);
        $this->teacher = User::factory()->create();
    });

    it('recommends courses based on subject match', function () {
        $course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'is_published' => true,
            'price' => 50,
        ]);

        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$this->subject->id],
        ]);

        $recommendations = $this->engine->recommendCourses($this->student, 5);

        expect($recommendations)->toHaveCount(1);
        expect($recommendations[0]['course']->id)->toBe($course->id);
    });

    it('excludes already enrolled courses', function () {
        $course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'is_published' => true,
        ]);

        CourseEnrollment::create([
            'course_id' => $course->id,
            'student_id' => $this->student->id,
            'enrolled_at' => now(),
        ]);

        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$this->subject->id],
        ]);

        $recommendations = $this->engine->recommendCourses($this->student, 5);

        $courseIds = collect($recommendations)->pluck('course.id')->toArray();
        expect($courseIds)->not->toContain($course->id);
    });
});

describe('Time Slot Recommendations', function () {
    beforeEach(function () {
        $this->teacherUser = User::factory()->create();
        $this->teacher = TeacherProfile::factory()->create([
            'user_id' => $this->teacherUser->id,
            'is_active' => true,
        ]);
    });

    it('recommends time slots', function () {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacher->id,
            'status' => 'available',
            'start_at' => now()->addDay()->setTime(10, 0),
            'end_at' => now()->addDay()->setTime(11, 0),
        ]);

        $recommendations = $this->engine->recommendTimeSlots($this->student, $this->teacher, 5);

        expect($recommendations)->toHaveCount(1);
        expect($recommendations[0]['slot']->id)->toBe($slot->id);
    });

    it('prefers slots in the near future', function () {
        $nearSlot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacher->id,
            'status' => 'available',
            'start_at' => now()->addDay()->setTime(10, 0),
            'end_at' => now()->addDay()->setTime(11, 0),
        ]);

        $farSlot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacher->id,
            'status' => 'available',
            'start_at' => now()->addWeeks(2)->setTime(10, 0),
            'end_at' => now()->addWeeks(2)->setTime(11, 0),
        ]);

        $recommendations = $this->engine->recommendTimeSlots($this->student, $this->teacher, 5);

        $nearRec = collect($recommendations)->first(fn ($r) => $r['slot']->id === $nearSlot->id);
        $farRec = collect($recommendations)->first(fn ($r) => $r['slot']->id === $farSlot->id);

        expect($nearRec['score'])->toBeGreaterThan($farRec['score']);
    });
});

describe('Learning Path Suggestions', function () {
    beforeEach(function () {
        $this->subject = Subject::factory()->create();
        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$this->subject->id],
        ]);
    });

    it('suggests learning path for each preferred subject', function () {
        $suggestions = $this->engine->suggestLearningPath($this->student);

        expect($suggestions)->toHaveCount(1);
        expect($suggestions[0]['subject']->id)->toBe($this->subject->id);
    });

    it('provides appropriate next steps based on progress', function () {
        // New student (0% progress)
        $suggestions = $this->engine->suggestLearningPath($this->student);
        expect($suggestions[0]['next_steps'])->toContain('Book an introductory session');
    });
});

describe('Engagement Analysis', function () {
    it('calculates engagement score', function () {
        StudentLearningInsight::create([
            'student_id' => $this->student->id,
            'total_bookings' => 10,
            'completed_lessons' => 8,
            'courses_completed' => 2,
            'engagement_score' => 75.5,
        ]);

        $analysis = $this->engine->analyzeEngagement($this->student);

        expect((float) $analysis['engagement_score'])->toBe(75.5);
    });

    it('provides engagement recommendations', function () {
        StudentLearningInsight::create([
            'student_id' => $this->student->id,
            'engagement_score' => 30,
            'courses_completed' => 0,
        ]);

        $analysis = $this->engine->analyzeEngagement($this->student);

        expect($analysis['recommendations'])->toBeArray();
        expect($analysis['recommendations'])->not->toBeEmpty();
    });
});

describe('Recommendation Storage', function () {
    it('stores teacher recommendations', function () {
        $subject = Subject::factory()->create();
        $teacherUser = User::factory()->create();
        $teacher = TeacherProfile::factory()->create([
            'user_id' => $teacherUser->id,
            'is_active' => true,
        ]);
        $teacher->subjects()->attach($subject);

        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$subject->id],
        ]);

        $this->engine->recommendTeachers($this->student, 5);

        $this->assertDatabaseHas('ai_recommendations', [
            'user_id' => $this->student->id,
            'type' => 'teacher',
        ]);
    });

    it('stores course recommendations', function () {
        $subject = Subject::factory()->create();
        $teacher = User::factory()->create();

        Course::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'is_published' => true,
        ]);

        UserLearningPreference::create([
            'user_id' => $this->student->id,
            'preferred_subjects' => [$subject->id],
        ]);

        $this->engine->recommendCourses($this->student, 5);

        $this->assertDatabaseHas('ai_recommendations', [
            'user_id' => $this->student->id,
            'type' => 'course',
        ]);
    });
});
