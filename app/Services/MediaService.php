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
    public function generateVideoThumbnail($mediaFile)
    {
        $storagePath = storage_path('app');

        $videoPath = $storagePath . '/' . $mediaFile->filename;
        $thumbnailFilename = md5('thumbnail'.$mediaFile->filename.time()).'.jpg';

        $video = new \ffmpeg_movie($videoPath, false);
        $frame = $video->getFrame(10);

        if ($frame) {
            $gdImage = $frame->toGDImage();
            if ($gdImage) {
                imagepng($gdImage, $thumbnailFilename);
                imagedestroy($gdImage);
            }
        }
        
        return $thumbnailFilename;
    }

}