<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected DiscountService $discountService
    ) {}

    public function show(Course $course): View
    {
        // Only show published courses to students
        if (! $course->is_published && ! auth()->user()->isAdmin()) {
            abort(404);
        }

        $course->load(['teacher', 'subject', 'lessons' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $isEnrolled = $course->isEnrolledBy(auth()->user());

        return view('student.courses.show', compact('course', 'isEnrolled'));
    }

    public function validateDiscount(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->discountService->validateDiscount(
            $request->code,
            auth()->user(),
            $course->price
        );

        return response()->json($result);
    }
}
