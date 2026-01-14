<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class CourseSeeder extends Seeder
{

    public function run(): void
    {
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run DemoUserSeeder first.');

            return;
        }

        $subjects = Subject::where('is_active', true)->get();

        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Please run SampleDataSeeder first.');

            return;
        }

        foreach ($teachers as $teacher) {
            $teacherProfile = $teacher->teacherProfile;

            if (! $teacherProfile) {
                continue;
            }

            // Get subjects this teacher teaches
            $teacherSubjects = $teacherProfile->subjects()->where('is_active', true)->get();

            if ($teacherSubjects->isEmpty()) {
                continue;
            }

            // Create 1-2 courses per teacher
            $numCourses = rand(1, 2);

            for ($i = 0; $i < $numCourses; $i++) {
                $subject = $teacherSubjects->random();

                $title = "{$subject->name} Fundamentals - Part ".($i + 1);
                $courseService = App::make(CourseService::class);

                $course = Course::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'title' => $title,
                    'slug' => $courseService->generateSlug($title),
                    'description' => "Learn the fundamentals of {$subject->name} through this comprehensive recorded course. Perfect for beginners and intermediate students.",
                    'price' => rand(15, 50),
                    'currency' => 'BHD',
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 30)),
                ]);

                // Create 3-5 lessons per course
                $numLessons = rand(3, 5);

                for ($j = 0; $j < $numLessons; $j++) {
                    $isPreview = $j === 0; // First lesson is preview

                    CourseLesson::create([
                        'course_id' => $course->id,
                        'title' => "Lesson ".($j + 1).": Introduction to Topic ".($j + 1),
                        'summary' => "In this lesson, we'll cover the basics of topic ".($j + 1).".",
                        'sort_order' => $j,
                        'video_provider' => 'youtube',
                        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Demo URL
                        'duration_seconds' => rand(300, 1800), // 5-30 minutes
                        'is_free_preview' => $isPreview,
                    ]);
                }
            }
        }

        $this->command->info('Demo courses created successfully!');
    }
}
