<?php

namespace App\Repositories;

use App\Entry;
use App\File;
use App\Image;
use App\Location;
use App\Services\AuthService;
use App\Trip;
use App\User;

/**
 * Class TripRepository
 * @package App\Repositories
 */
class TripRepository {

    /**
     * TripRepository constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param $hash
     * @return mixed
     */
    public function getTripByHash($hash)
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
            ->where('hash', $hash)
            ->first();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getTripsForUser()
    {
        return Trip::with(['locations' => function($query) {
                $query->orderBy('order', 'ASC');
            }])
            ->where('user_id', $this->authService->getUser()->id)
            ->get();
    }

    /**
     * @param Trip $trip
     * @throws \Exception
     */
    public function deleteTrip(Trip $trip)
    {
        $trip->delete();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createTrip($data)
    {
        return Trip::create([
            'name' => $data['name'],
            'user_id' => $this->authService->getUser()->id
        ]);
    }

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