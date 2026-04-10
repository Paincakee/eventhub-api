<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function __construct(
        private readonly EventRepository $repository)
    {}

    /**
     * Display a listing of the events.
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
       $events = $this->repository->getAll($request)
            ->paginate(
                $request->integer('itemsPerPage'),
                page: $request->integer('page'),
            );

        return EventResource::collection($events);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param StoreEventRequest $request
     *
     * @return EventResource
     */
    public function store(StoreEventRequest $request): EventResource
    {
        $validated = $request->validated();
        $event = Event::create($validated);

        return new EventResource($event->refresh());
    }

    /**
     * Display the specified event.
     *
     * @param Event $event
     *
     * @return EventResource
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    /**
     * Update the specified event in storage.
     *
     * @param UpdateEventRequest $request
     * @param Event $event
     *
     * @return EventResource
     */
    public function update(UpdateEventRequest $request, Event $event): EventResource
    {
        $validated = $request->validated();
        $event->update($validated);

        return new EventResource($event->refresh());
    }

    /**
     * Soft deletes the event.
     *
     * @param Event $event
     *
     * @return Response
     */
    public function destroy(Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }
}
