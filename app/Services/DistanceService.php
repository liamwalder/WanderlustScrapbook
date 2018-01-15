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
     * @param $locations
     * @return float|int
     */
    public function getDistancesForLatitudeAndLongitudes($locations)
    {
        $miles = 0;

        foreach ($locations as $key => $location) {
            if ($key > 0) {

                $firstPosition = $locations[($key - 1)];
                $secondPosition = $locations[$key];

                $latitudeOne = $firstPosition->latitude;
                $latitudeTwo = $secondPosition->latitude;

                $longitudeOne = $firstPosition->longitude;
                $longitudeTwo = $secondPosition->longitude;

                $theta = $longitudeOne - $longitudeTwo;
                $dist = sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo)) +  cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles += $dist * 60 * 1.1515;

            }
        }

        $miles = number_format(round($miles));
        return $miles;
    }


}