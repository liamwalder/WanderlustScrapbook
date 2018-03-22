<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;
use App\Trip;
use Lakshmaji\Thumbnail\Facade\Thumbnail;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;


/**
 * Class MediaService
 * @package App\Services
 */
class MediaService {

    /**
     * @param $mediaFile
     * @return mixed
     */
    public function generateThumbnail($file, $filename)
    {
        if (strpos($file->getClientMimeType(), 'video') !== false) {
            $storagePath = storage_path('app/');

            $videoPath = storage_path('app/' . $filename);
            $thumbnailFilename = 'thumbnail'.$filename.'.jpg';

            $thumbnailPath = $storagePath . $thumbnailFilename;

            shell_exec("ffmpeg -i $videoPath -ss 00:00:01 -vframes 1 $thumbnailPath");

            return $thumbnailFilename;
        }

        return $filename;
    }

    /**
     * @param $filePath
     */
    public function compressImage($filePath)
    {
        ImageOptimizer::optimize($filePath);
    }

}