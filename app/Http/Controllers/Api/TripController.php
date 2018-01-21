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
use App\Services\DistanceService;
use App\Services\TripService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TripController
 * @package App\Http\Controllers\Api
 */
class TripController extends Controller {

    /**
     * @param Request $request
     * @param $hash
     * @param TripService $tripService
     * @param TripRepository $tripRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(
        Request $request,
        $hash,
        TripService $tripService,
        TripRepository $tripRepository
    ) {
        $trip = $tripRepository->getTripByHash($hash);

        return response()->json([
            'trip' => [
                'id' => $trip->id,
                'name' => $trip->name,
                'locationCount' => $trip->locations->count(),
                'miles' => $tripService->getTripMiles($trip),
                'center' => $tripService->getTripCenter($trip),
                'countries' => $tripService->getCountryCountForTrip($trip),
            ],
            'activity' => [
                'files' => $tripService->getFilesForTrip($trip),
                'entries' => $tripService->getEntriesForTrip($trip)
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