<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'event_id' => [
                'required',
                'exists:events,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('registrations', 'email')
                    ->where('event_id', $this->input('event_id')),
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('registrations', 'user_id')
                    ->where('event_id', $this->event_id),
            ],
        ];
    }
}
