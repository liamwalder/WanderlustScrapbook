<?php

namespace App\Repositories;

use App\Entry;
use App\File;
use App\Image;
use App\Location;

/**
 * Class FileRepository
 * @package App\Repositories
 */
class FileRepository {

    /**
     * @param $id
     * @return mixed
     */
    public function getFile($id)
    {
        return File::findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFiles()
    {
        return File::orderBy('created_at', 'DESC')->get();
    }

    /**
     * @param $reference
     * @return mixed
     */
    public function getFileByFilename($filename)
    {
        return File::where('filename', $filename)->first();
    }

    /**
     * @param $UUID
     * @return mixed
     */
    public function getFileByUUID($UUID)
    {
        return File::where('uuid', $UUID)->first();
    }

}