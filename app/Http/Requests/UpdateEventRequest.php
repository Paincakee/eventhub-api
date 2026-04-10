<?php

namespace App\Http\Requests;


class UpdateEventRequest extends StoreEventRequest
{
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'location' => 'sometimes|string|max:255',
            'max_attendees' => 'sometimes|integer|min:1',
            'image_url' => 'sometimes|url',
            'is_published' => 'sometimes|boolean',
        ];
    }
}
