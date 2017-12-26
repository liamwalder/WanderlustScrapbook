<?php

namespace App\Repositories;

use App\Entry;
use App\EntryLocation;
use App\Location;

/**
 * Class EntryLocationRepository
 * @package App\Repositories
 */
class EntryLocationRepository {

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getMarkers()
    {
        return EntryLocation::select('latitude', 'longitude')->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createEntryLocation($data, $entry, $location)
    {
        return EntryLocation::create([
            'entry_id' => $entry,
            'location_id' => $location,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
    }

}