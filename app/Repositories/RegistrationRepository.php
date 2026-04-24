<?php

namespace App\Repositories;

use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Http\Resources\RegistrationResource;
use App\Models\Registration;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RegistrationRepository
{
    /**
     * Return query builder for all registrations.
     *
     * @param Request $request
     * @return QueryBuilder<Registration>
     */
    public function getAll(Request $request): QueryBuilder
    {
        /** @var QueryBuilder<Registration> $queryBuilder */
        $queryBuilder = QueryBuilder::for(Registration::class, $request);

        $queryBuilder
            ->with('event')
            ->allowedFilters([
                AllowedFilter::exact('event_id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts([
                'name',
                'event.name',
                'email',
                'status',
            ])
            ->select('events.*');

        return $queryBuilder;
    }

    /**
     * @param Registration $registration
     *
     * @return Registration
     */
    public function getRegistration(Registration $registration): Registration
    {
        return RegistrationResource::make($registration->load(['event', 'user']));
    }

    /**
     * @param StoreRegistrationRequest|UpdateRegistrationRequest $request
     * @param Registration|null $registration
     *
     * @return Registration
     */
    public function updateOrCreate(StoreRegistrationRequest|UpdateRegistrationRequest $request): Registration
    {
        $validated = $request->validated();

        return Registration::updateOrCreate([
            'event_id' => $validated['event_id'],
            'user_id' => $validated['user_id'],
            'email' => $validated['email'],
        ], $validated);
    }
}
