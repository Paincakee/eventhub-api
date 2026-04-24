<?php

namespace App\Http\Requests;


class UpdateRegistrationRequest extends StoreEventRequest
{
    public function rules(): array
    {
        return [
            'event_id' => 'sometimes|exists:events,id',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:registrations,email',
        ];
    }
}
