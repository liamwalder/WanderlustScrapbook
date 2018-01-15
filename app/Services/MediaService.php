<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;
use App\Trip;
use Lakshmaji\Thumbnail\Facade\Thumbnail;


/**
 * Class MediaService
 * @package App\Services
 */
class MediaService {

    /**
     * @param $mediaFile
     * @return mixed
     */
    public function generateThumbnail($mediaFile)
    {
        if (strpos($mediaFile->mime, 'video') !== false) {
            $storagePath = storage_path('app/');

            $videoPath = storage_path('app/' . $mediaFile->filename);
            $thumbnailFilename = md5('thumbnail'.$mediaFile->filename.time()).'.jpg';

            $thumbnailPath = $storagePath . $thumbnailFilename;

            shell_exec("ffmpeg -i $videoPath -ss 00:00:01 -vframes 1 $thumbnailPath");

            return $thumbnailFilename;
        }

        return $mediaFile->filename;
    }

}