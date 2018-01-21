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
     * @param $data
     * @param $tripId
     * @return Location
     */
    public function createLocation($data, $tripId)
    {
        $lastLocation = Location::orderBy('id', 'desc')->where('trip_id', $tripId)->first();
        $thisLocationOrder = is_null($lastLocation) ? 0 : ($lastLocation->order + 1);

        $requestLocation = $data['location'];

        $location = new Location();
        $location->fill([
            'trip_id' => $tripId,
            'order' => $thisLocationOrder,
            'name' => $data['name'],
            'country' => $data['country'],
            'latitude' => $requestLocation['lat'],
            'longitude' => $requestLocation['lng'],
            'to' =>$data['to'] ? new \DateTime($data['to']) : null,
            'from' => $data['from'] ? new \DateTime($data['from']) : null
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
     * @param Location $location
     * @throws \Exception
     */
    public function deleteLocation(Location $location)
    {
        $location->delete();
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

    /**
     * @param $latitude
     * @param $longitude
     * @param $tripId
     * @return mixed
     */
    public function getLocationByLatitudeLongitudeForTrip($latitude, $longitude, $tripId)
    {
        return Location::where('latitude', $latitude)
            ->where('longitude', $longitude)
            ->where('trip_id', $tripId)
            ->first();
    }

}