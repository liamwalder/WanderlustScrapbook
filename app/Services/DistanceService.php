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

    /**
     *  $data = array
     * (
     *   0 = > array(45.849382, 76.322333),
     *   1 = > array(45.843543, 75.324143),
     *   2 = > array(45.765744, 76.543223),
     *   3 = > array(45.784234, 74.542335)
     * );
     *
     * @param $data
     * @return array|bool
     */
    public function getCenterOfMultipleLatitudeLongitudes($data) 
    {

        if (!is_array($data)) return FALSE;

        $num_coords = count($data);

        $X = 0.0;
        $Y = 0.0;
        $Z = 0.0;

        foreach ($data as $coord)
        {
            $lat = $coord[0] * pi() / 180;
            $lon = $coord[1] * pi() / 180;

            $a = cos($lat) * cos($lon);
            $b = cos($lat) * sin($lon);
            $c = sin($lat);

            $X += $a;
            $Y += $b;
            $Z += $c;
        }

        $X /= $num_coords;
        $Y /= $num_coords;
        $Z /= $num_coords;

        $lon = atan2($Y, $X);
        $hyp = sqrt($X * $X + $Y * $Y);
        $lat = atan2($Z, $hyp);

        return [
            'lat' => $lat * 180 / pi(),
            'lng' => $lon * 180 / pi()
        ];
        
    }

}