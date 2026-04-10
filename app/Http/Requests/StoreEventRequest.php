<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'string',
            'start_date' => 'required|datetime',
            'end_date' => 'datetime',
            'location' => 'string|max:255',
            'max_attendees' => 'required|integer|min:1',
            'image_url' => 'nullable|url',
            'is_published' => 'boolean',
        ];
    }
}
