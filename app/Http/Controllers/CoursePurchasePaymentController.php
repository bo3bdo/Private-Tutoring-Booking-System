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
                ->title('غير متاح')
                ->message('هذا الكورس غير متاح للشراء')
                ->send();

            return back();
        }

        // Check if already enrolled
        if ($course->isEnrolledBy($student)) {
            notify()->info()
                ->title('مسجل مسبقاً')
                ->message('أنت مسجل في هذا الكورس بالفعل')
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
