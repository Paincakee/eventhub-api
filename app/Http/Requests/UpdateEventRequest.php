<?php

namespace App\Http\Requests;


class UpdateEventRequest extends StoreEventRequest
{
    public function rules(): array
    {
        return [
            'title' => 'somtimes|string|max:255',
            'description' => 'somtimes|string',
            'start_date' => 'somtimes|datetime',
            'end_date' => 'somtimes|datetime',
            'location' => 'somtimes|string|max:255',
            'max_attendees' => 'somtimes|integer|min:1',
            'image_url' => 'somtimes|url',
            'is_published' => 'somtimes|boolean',
        ];
    }
}
