<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventRepository
{
    /**
     * Return query builder for all events.
     *
     * @param Request $request
     * @return QueryBuilder<Event>
     */
    public function getAll(Request $request): QueryBuilder
    {
        /** @var QueryBuilder<Event> $queryBuilder */
        $queryBuilder = QueryBuilder::for(Event::class, $request);

        /** @var Builder<Event> $builder */
        $queryBuilder
            ->allowedFilters([
                AllowedFilter::scope('scopePublished', 'published'),
            ])
            ->allowedSorts([
                'title',
                'start_date',
                'end_date',
                'is_published',
            ])
            ->select('events.*');

        return $queryBuilder;
    }
}
