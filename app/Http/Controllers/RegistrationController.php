<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Http\Resources\RegistrationResource;
use App\Models\Registration;
use App\Repositories\RegistrationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly RegistrationRepository $repository
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $registrations = $this->repository->getAll($request)
            ->paginate(
                $request->integer('itemsPerPage'),
                page: $request->integer('page'),
            );

        return RegistrationResource::collection($registrations);
    }

    /**
     * @param StoreRegistrationRequest $request
     * @return Registration
     */
    public function store(StoreRegistrationRequest $request): Registration
    {
        return $this->repository->updateOrCreate($request);
    }

    /**
     * @param Registration $registration
     * @return Registration
     */
    public function show(Registration $registration): Registration
    {
        return $this->repository->getRegistration($registration);
    }

    /**
     * @param UpdateRegistrationRequest $request
     * @return Registration
     */
    public function update(UpdateRegistrationRequest $request): Registration
    {
        return $this->repository->updateOrCreate($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration): Response
    {
        $registration->delete();

        return response()->noContent();
    }
}
