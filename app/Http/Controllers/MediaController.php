<?php

namespace App\Http\Controllers;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Class MediaController
 * @package App\Http\Controllers
 */
class MediaController extends Controller {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function serve($filename, FileRepository $fileRepository)
    {
        $filePath = storage_path() . '/app/' . $filename;
        $fileContents = File::get($filePath);
        $mimeType = File::mimeType($filePath);
        return Response::make($fileContents, 200, array('Content-Type' => $mimeType));
    }

}