<?php

namespace App\Repositories;

use App\Entry;
use App\File;
use App\Image;
use App\Location;
use App\Trip;

/**
 * Class TripRepository
 * @package App\Repositories
 */
class TripRepository {

    /**
     * @param $id
     * @return mixed
     */
   public function getTrip($id)
   {
       return Trip::with(['locations' => function($query) {
                $query->orderBy('order', 'ASC');
                $query->with(['entries' => function($query) {
                   $query->orderBy('created_at', 'DESC');
                   $query->with('files');
                   $query->with(['entryLocations' => function($query) {
                        $query->with(['entry' => function($query) {
                            $query->with('entryLocations');
                        }]);
                   }]);
                }]);
           }])
           ->where('id', $id)
           ->first();
   }


    /**
     * @param Trip $trip
     * @param $data
     * @return Trip
     */
    public function updateTrip(Trip $trip, $data)
    {
        $trip->fill([
            'name' => isset($data['name']) ? $data['name'] : $trip->name
        ]);
        $trip->save();
        return $trip;
    }
    
}