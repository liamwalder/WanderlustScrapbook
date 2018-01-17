<?php

namespace App\Repositories;

use App\Location;
use Symfony\Component\HttpFoundation\Request;

class LocationRepository {


    /**
     * @param $id
     * @return mixed
     */
    public function getLocation($id)
    {
        return Location::findOrFail($id);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLocations()
    {
        return Location::with(['entries' => function($query) {
                $query->orderBy('created_at', 'DESC');
                $query->with('files');
                $query->with('entryLocations');
            }])
            ->with(['files' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->orderBy('order', 'ASC')
            ->get();
    }

    /**
     * @param Request $request
     * @param $tripId
     * @return Location
     */
    public function createLocation(Request $request, $tripId)
    {
        $lastLocation = Location::orderBy('id', 'desc')->where('trip_id', $tripId)->first();
        $thisLocationOrder = is_null($lastLocation) ? 0 : ($lastLocation->order + 1);

        $requestLocation = $request->get('location');

        $location = new Location();
        $location->fill([
            'trip_id' => $tripId,
            'order' => $thisLocationOrder,
            'name' => $request->get('name'),
            'country' => $request->get('country'),
            'latitude' => $requestLocation['lat'],
            'longitude' => $requestLocation['lng'],
            'to' => $request->get('to') ? new \DateTime($request->get('to')) : null,
            'from' => $request->get('from') ? new \DateTime($request->get('from')) : null
        ]);

        $location->save();

        return $location;
    }

    /**
     * @param Location $location
     * @param $data
     * @return Location
     */
    public function updateLocation(Location $location, $data)
    {
        $location->fill([
            'name' => isset($data['name']) ? $data['name'] : $location->name,
            'to' => isset($data['to']) ? new \DateTime($data['to']) : $location->to,
            'from' => isset($data['from']) ? new \DateTime($data['from']) : $location->from
        ]);
        $location->save();
        return $location;
    }

    /**
     * @return mixed
     */
    public function getMarkers()
    {
        return Location::select('latitude', 'longitude')
            ->orderBy('order', 'ASC')
            ->get();
    }

}