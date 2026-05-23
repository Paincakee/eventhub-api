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
                Rule::unique('registrations', 'email')
                    ->where('event_id', $this->input('event_id') ?? $this->route('registration')->event_id)
                    ->ignore($this->route('registration')->id),
            ],
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('registrations', 'user_id')
                    ->where('event_id', $this->input('event_id') ?? $this->route('registration')->event_id)
                    ->ignore($this->route('registration')->id),
            ],
        ];
    }
}
