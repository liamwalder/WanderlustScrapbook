<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;
use App\Trip;

/**
 * Class TripService
 * @package App\Services
 */
class TripService {

    /**
     * @var DistanceService
     */
    public $distanceService;

    /**
     * TripService constructor.
     * @param DistanceService $distanceService
     */
    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    /**
     * @param Trip $trip
     * @return array
     */
    public function getEntriesForTrip(Trip $trip)
    {
        $entries = [];
        foreach ($trip->locations as $location) {
            foreach ($location->entries as $entry) {
                $entries[] = $entry;
            }
        }
        return $entries;
    }

    /**
     * @param Trip $trip
     * @return array
     */
    public function getFilesForTrip(Trip $trip)
    {
        $files = [];
        foreach ($trip->locations as $location) {
            foreach ($location->files as $file) {
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * @param Trip $trip
     * @return array
     */
    public function getLocationsForTripEntries(Trip $trip)
    {
        $entryLocations = [];
        foreach ($trip->locations as $location) {
            foreach ($location->entries as $entry) {
                foreach ($entry->entryLocations as $entryLocation) {
                    $entryLocations[] = $entryLocation;
                }

            }
        }
        return $entryLocations;
    }

    /**
     * @param Trip $trip
     * @return mixed
     */
    public function getCountryCountForTrip(Trip $trip)
    {
        $locationsGroupedByCountry = $trip->locations->groupBy('country');
        return $locationsGroupedByCountry->count();
    }

    /**
     * @param Trip $trip
     * @return array|bool
     */
    public function getTripCenter(Trip $trip)
    {
        $latitudesAndLongitudes = [];
        foreach ($trip->locations as $location) {
            $latitudesAndLongitudes[] = [$location->latitude, $location->longitude];
        }

        $center = $this->distanceService->getCenterOfMultipleLatitudeLongitudes($latitudesAndLongitudes);

        return $center;
    }

    /**
     * @param Trip $trip
     * @return float|int
     */
    public function getTripMiles(Trip $trip)
    {
        $miles = 0;
        $locations = $trip->locations;
        
        foreach ($locations as $key => $location) {
            if ($key > 0) {
                $locationOne = $locations[($key - 1)];
                $locationTwo = $location;
                $miles += $this->distanceService->getDistancesForLatitudeAndLongitude($locationOne->latitude, $locationTwo->latitude, $locationOne->longitude, $locationTwo->longitude);
            }
        }

        return number_format(round($miles));
    }


}