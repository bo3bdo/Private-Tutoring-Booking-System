<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'in:stripe,benefitpay'],
        ];
    }

    public function messages(): array
    {
        return [
            'provider.required' => 'Please select a payment provider.',
            'provider.in' => 'Invalid payment provider selected.',
        ];
    }
}
