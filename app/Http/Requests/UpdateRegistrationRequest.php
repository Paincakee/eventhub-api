<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UpdateRegistrationRequest extends StoreEventRequest
{
    public function rules(): array
    {
        $userId = $this->user_id;

        return [
            'event_id' => [
                'sometimes',
                'exists:events,id',
            ],
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'email',
                'unique:registrations',
            ],
            'user_id' => [
                'sometimes',
                'exists:users,id',
                Rule::unique('registrations', 'user_id')->whereNull('deleted_at')->ignore($userId),
            ],
        ];
    }
}
