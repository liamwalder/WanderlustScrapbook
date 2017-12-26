<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MediaController
 * @package App\Http\Controllers\Api
 */
class MediaController extends Controller {

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $mediaFiles = [];

        foreach ($request->files->all() as $file) {
            $extension = $file->getClientOriginalExtension();

            $filename = md5($file->getFilename().time());

            Storage::disk('local')->put($filename.'.'.$extension,  File::get($file));

            $mediaFile = new \App\File();

            $mediaFile->mime = $file->getClientMimeType();
            $mediaFile->original_filename = $file->getClientOriginalName();
            $mediaFile->filename = $filename.'.'.$extension;

            $mediaFile->save();

            $mediaFiles[] = $mediaFile;
        }

        return response()->json($mediaFiles, 200);
    }


}