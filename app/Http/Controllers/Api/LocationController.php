<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Repositories\LocationRepository;
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        
        $lastLocation = Location::orderBy('id', 'desc')->first();
        $thisLocationOrder = is_null($lastLocation) ? 0 : ($lastLocation->order + 1);

        $requestLocation = $request->get('location');

        $location = new Location();
        $location->fill([
            'order' => $thisLocationOrder,
            'name' => $requestLocation['name'],
            'latitude' => $requestLocation['lat'],
            'longitude' => $requestLocation['lng'],
            'to' => $request->get('to') ? new \DateTime($request->get('to')) : null,
            'from' => $request->get('from') ? new \DateTime($request->get('from')) : null
         ]);
        $location->save();

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


}