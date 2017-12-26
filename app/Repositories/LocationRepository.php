<?php

namespace App\Repositories;

use App\Location;

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
     * @param Location $location
     * @param $data
     * @return Location
     */
    public function updateLocation(Location $location, $data)
    {
        $location->fill([
            'to' => isset($data['to']) ? new \DateTime($data['to']) : null,
            'from' => isset($data['from']) ? new \DateTime($data['from']) : null
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