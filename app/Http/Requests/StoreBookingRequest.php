<?php

namespace App\Http\Requests;

use App\Enums\LessonMode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => ['required', 'exists:teacher_time_slots,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'lesson_mode' => ['required', Rule::enum(LessonMode::class)],
            'location_id' => [
                Rule::requiredIf($this->lesson_mode === LessonMode::InPerson->value),
                'nullable',
                'exists:locations,id',
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Time slot is required.',
            'time_slot_id.exists' => 'Selected time slot does not exist.',
            'subject_id.required' => 'Subject is required.',
            'subject_id.exists' => 'Selected subject does not exist.',
            'lesson_mode.required' => 'Please select a lesson mode (Online or In Person).',
            'lesson_mode.enum' => 'Invalid lesson mode selected.',
            'location_id.required' => 'Please select a location for in-person lessons.',
            'location_id.exists' => 'Selected location does not exist.',
        ];
    }
}
