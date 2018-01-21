<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Repositories\LocationRepository;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LocationController
 * @package App\Http\Controllers\Api
 */
class LocationController extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $tripId, LocationRepository $locationRepository)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        
        $location = $locationRepository->createLocation($request, $tripId);

        return response()->json($location, 201);
    }


    /**
     * @param Request $request
     * @param $id
     * @param LocationRepository $locationRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, LocationRepository $locationRepository)
    {
        $data = $request->all();
        $location = $locationRepository->getLocation($id);
        $location = $locationRepository->updateLocation($location, $data);

        return response()->json($location, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param LocationRepository $locationRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id, LocationRepository $locationRepository)
    {
        $location = $locationRepository->getLocation($id);
        $locationRepository->deleteLocation($location);

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param LocationRepository $locationRepository
     * @param FileService $fileService
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachFiles(Request $request, $id, LocationRepository $locationRepository, FileService $fileService)
    {
        $location = $locationRepository->getLocation($id);
        $fileService->attachFilesToLocation($location, $request->get('files'));

        return response()->json([], 200);
    }


}