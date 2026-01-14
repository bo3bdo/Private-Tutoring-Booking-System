<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Http\Requests\Student\PurchaseCourseRequest;
use App\Models\Course;
use App\Services\CoursePurchaseService;
use Illuminate\Http\RedirectResponse;

class CoursePurchasePaymentController extends Controller
{
    public function __construct(
        protected CoursePurchaseService $purchaseService
    ) {}

    public function purchase(PurchaseCourseRequest $request, Course $course): RedirectResponse
    {
        $student = auth()->user();

        // Check if course is published
        if (! $course->is_published) {
            return back()->withErrors(['error' => 'This course is not available for purchase.']);
        }

        // Check if already enrolled
        if ($course->isEnrolledBy($student)) {
            return redirect()->route('student.my-courses.learn', $course->slug)
                ->with('info', 'You are already enrolled in this course.');
        }

        try {
            $provider = PaymentProvider::from($request->provider);
            $payment = $this->purchaseService->startPurchase($course, $student, $provider);

            return redirect($payment->checkout_url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
