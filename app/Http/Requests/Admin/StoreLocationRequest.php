<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:locations,name'],
            'address' => ['required', 'string', 'max:500'],
            'map_url' => ['nullable', 'url', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Location name is required.',
            'name.unique' => 'A location with this name already exists.',
            'address.required' => 'Address is required.',
            'map_url.url' => 'Map URL must be a valid URL.',
        ];
    }
}
