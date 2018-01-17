<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;

/**
 * Class DistanceService
 * @package App\Services
 */
class DistanceService {

    /**
     * @param $latitudeOne
     * @param $latitudeTwo
     * @param $longitudeOne
     * @param $longitudeTwo
     * @return float
     */
    public function getDistancesForLatitudeAndLongitude($latitudeOne, $latitudeTwo, $longitudeOne, $longitudeTwo)
    {
        $theta = $longitudeOne - $longitudeTwo;
        $dist = sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo)) +  cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles;
    }


}