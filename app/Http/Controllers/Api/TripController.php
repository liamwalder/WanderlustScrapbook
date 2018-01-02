<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Repositories\EntryLocationRepository;
use App\Repositories\EntryRepository;
use App\Repositories\FileRepository;
use App\Repositories\ImageRepository;
use App\Repositories\LocationRepository;
use App\Repositories\TripRepository;
use App\Services\TripService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TripController
 * @package App\Http\Controllers\Api
 */
class TripController extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(
        Request $request,
        $id,
        TripService $tripService,
        TripRepository $tripRepository
    ) {

        $trip = $tripRepository->getTrip($id);

        return response()->json([
            'trip' => [
                'id' => $trip->id,
                'name' => $trip->name
            ],
            'activity' => [
                'entries' => $tripService->getEntriesForTrip($trip),
                'files' => $tripService->getFilesForTrip($trip),
            ],
            'markers' => [
                'locations' => $trip->locations,
                'entryLocations' => $tripService->getLocationsForTripEntries($trip)
            ],
            'locations' => $trip->locations
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param TripRepository $tripRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Request $request,
        $id,
        TripRepository $tripRepository
    ) {
        $data = $request->all();
        $trip = $tripRepository->getTrip($id);
        $trip = $tripRepository->updateTrip($trip, $data);

        return response()->json($trip, 200);
    }


}