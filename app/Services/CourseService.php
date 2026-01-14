<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Str;

class CourseService
{
    public function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function publish(Course $course): void
    {
        $course->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(Course $course): void
    {
        $course->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
