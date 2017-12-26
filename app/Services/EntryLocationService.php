<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\EntryLocationRepository;
use App\Repositories\FileRepository;

/**
 * Class EntryLocationService
 * @package App\Services
 */
class EntryLocationService {

    /**
     * @var
     */
    public $fileRepository;

    /**
     * ImageService constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository, EntryLocationRepository $entryLocationRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->entryLocationRepository = $entryLocationRepository;
    }

    /**
     * @param Entry $entry
     * @param $fileReferences
     */
    public function attachLocationsToEntry(Entry $entry, $entryLocations)
    {
        $entry->entryLocations()->delete();
        foreach ($entryLocations as $location) {
            $this->entryLocationRepository->createEntryLocation($location, $entry->id, $entry->location_id);
        }
    }


}