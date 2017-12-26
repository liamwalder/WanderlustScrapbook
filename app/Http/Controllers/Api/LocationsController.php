<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LocationsController
 * @package App\Http\Controllers\Api
 */
class LocationsController extends Controller {

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $locations = Location::orderBy('order', 'ASC')->get();
        return response()->json($locations, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $order = 0;
        foreach ($request->get('locations') as $location) {
            $existingLocation = Location::find($location['id']);
            $existingLocation->order = $order;
            $existingLocation->save();

            $order++;
        }

        return response()->json([], 200);
    }


}