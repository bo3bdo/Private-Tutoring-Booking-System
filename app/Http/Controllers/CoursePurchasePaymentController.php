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
            notify()->error()
                ->title(__('common.Not available'))
                ->message(__('common.This course is not available for purchase'))
                ->send();

            return back();
        }

        // Check if already enrolled
        if ($course->isEnrolledBy($student)) {
            notify()->info()
                ->title(__('common.Already enrolled'))
                ->message(__('common.You are already enrolled in this course'))
                ->send();

            return redirect()->route('student.my-courses.learn', $course->slug);
        }

        try {
            $provider = PaymentProvider::from($request->provider);
            $payment = $this->purchaseService->startPurchase($course, $student, $provider);

            return redirect($payment->checkout_url);
        } catch (\Exception $e) {
            notify()->error()
                ->title('خطأ')
                ->message($e->getMessage())
                ->send();

            return back();
        }
    }
}
