<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function createCheckout(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('view', $booking);

        $provider = PaymentProvider::from($request->provider);
        $amount = $booking->teacher->hourly_rate ?? 25.00;
        $currency = \App\Models\Setting::get('currency', 'BHD');

        $payment = $this->paymentService->createPayment($booking, $provider, $amount, $currency);

        return redirect($payment->checkout_url);
    }

    public function stripeSuccess(Payment $payment): RedirectResponse
    {
        if ($payment->isSucceeded()) {
            notify()->success()
                ->title(__('common.Payment successful'))
                ->message(__('common.Booking confirmed successfully'))
                ->send();

            return redirect()->route('student.bookings.show', $payment->booking);
        }

        notify()->warning()
            ->title(__('common.Processing'))
            ->message(__('common.Payment is still processing'))
            ->send();

        return redirect()->route('student.bookings.pay', $payment->booking);
    }

    public function stripeCancel(Payment $payment): RedirectResponse
    {
        notify()->error()
            ->title(__('common.Payment cancelled'))
            ->message(__('common.Payment cancelled'))
            ->send();

        return redirect()->route('student.bookings.pay', $payment->booking);
    }

    public function stripeWebhook(Request $request): Response
    {
        $this->paymentService->handleWebhook(
            PaymentProvider::Stripe,
            [
                'body' => $request->getContent(),
                'headers' => $request->headers->all(),
            ]
        );

        return response()->json(['received' => true]);
    }

    public function benefitpayWebhook(Request $request): Response
    {
        $this->paymentService->handleWebhook(
            PaymentProvider::BenefitPay,
            [
                'body' => $request->getContent(),
                'headers' => $request->headers->all(),
            ]
        );

        return response()->json(['received' => true]);
    }

    public function testComplete(Booking $booking): RedirectResponse
    {
        // Only allow in local/debug environment
        if (! app()->environment('local') && ! config('app.debug')) {
            abort(404);
        }

        $this->authorize('view', $booking);

        if (! $booking->isAwaitingPayment()) {
            notify()->warning()
                ->title(__('common.Payment not required'))
                ->message(__('common.This booking does not require payment'))
                ->send();

            return redirect()->route('student.bookings.show', $booking);
        }

        $amount = $booking->teacher->hourly_rate ?? 25.00;
        $currency = \App\Models\Setting::get('currency', 'BHD');

        // Create payment record
        $payment = \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'student_id' => $booking->student_id,
            'provider' => PaymentProvider::Stripe, // Using Stripe as default for test
            'amount' => $amount,
            'currency' => $currency,
            'status' => \App\Enums\PaymentStatus::Succeeded,
            'provider_reference' => 'test_'.uniqid(),
            'paid_at' => now(),
        ]);

        // Confirm the booking
        $this->paymentService->confirmPayment($payment);

        notify()->success()
            ->title(__('common.Payment successful'))
            ->message(__('common.Payment completed successfully! (Test Mode)'))
            ->send();

        return redirect()->route('student.bookings.show', $booking);
    }
}
