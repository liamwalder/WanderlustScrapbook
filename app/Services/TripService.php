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

}