<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;
use App\Repositories\LocationRepository;

/**
 * Class FileService
 * @package App\Services
 */
class LocationService {

    /**
     * LocationService constructor.
     * @param LocationRepository $locationRepository
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param $data
     * @param $tripId
     * @return mixed
     */
    public function handleDuplicateLocation($data, $tripId)
    {
        $duplicateLocation = $this->locationRepository->getLocationByLatitudeLongitudeForTrip($data['location']['lat'], $data['location']['lng'], $tripId);
        if ($duplicateLocation) {
            $newLatitude = $data['location']['lat'];
            $newLongitude = $data['location']['lng'];

            $latitudeDecimalCount = strlen(substr(strchr($data['location']['lat'], "."), 1));
            $longitudeDecimalCount = strlen(substr(strchr($data['location']['lng'], "."), 1));

            $latitudeSubtractFigure = 0 . '.' . str_repeat(0, ($latitudeDecimalCount - 1)) . 1;
            $longitudeSubtractFigure = 0 . '.' . str_repeat(0, ($longitudeDecimalCount - 1)) . 1;

            $latitude = $newLatitude - $latitudeSubtractFigure;
            $longitude = $newLongitude - $longitudeSubtractFigure;

            $data['location']['lat'] = $latitude;
            $data['location']['lng'] = $longitude;
        }

        return $data;
    }


}