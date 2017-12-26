<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Repositories\EntryLocationRepository;
use App\Repositories\EntryRepository;
use App\Repositories\FileRepository;
use App\Repositories\ImageRepository;
use App\Repositories\LocationRepository;
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
        LocationRepository $locationRepository,
        EntryRepository $entryRepository,
        FileRepository $fileRepository,
        EntryLocationRepository $entryLocationRepository
    ) {
        return response()->json([
            'activity' => [
                'entries' => $entryRepository->getEntries(),
                'files' => $fileRepository->getFiles(),
            ],
            'markers' => [
                'locations' => $locationRepository->getMarkers(),
                'entryLocations' => $entryLocationRepository->getMarkers()
            ],
            'locations' => $locationRepository->getLocations()
        ]);
    }


}