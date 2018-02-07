<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;

/**
 * Class FileService
 * @package App\Services
 */
class FileService {

    /**
     * @var
     */
    public $fileRepository;

    /**
     * ImageService constructor.
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Entry $entry
     * @param $fileReferences
     */
    public function attachFilesToEntry(Entry $entry, $fileReferences)
    {
        foreach ($fileReferences as $fileReference) {
            $file = $this->fileRepository->getFileByFilename($fileReference);
            if ($file) {
                $file->entry_id = $entry->id;
                $file->location_id = $entry->location_id;
                $file->save();
            }
        }
    }

    /**
     * @param Location $location
     * @param $fileReferences
     */
    public function attachFilesToLocation(Location $location, $fileReferences)
    {
        foreach ($fileReferences as $fileReference) {
            $file = $this->fileRepository->getFileByFilename($fileReference);
            if ($file) {
                $file->location_id = $location->id;
                $file->save();
            }
        }
    }

    /**
     * @param array $captions
     * @param $fileReferences
     */
    public function attachCaptionsToFiles(array $captions)
    {
        foreach ($captions as $caption) {
            $file = $this->fileRepository->getFileByUUID($caption['uuid']);
            $file->caption = $caption['caption'];
            $file->save();
        }
    }


}