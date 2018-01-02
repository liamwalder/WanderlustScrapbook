<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
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


    /**
     * @param Request $request
     * @param $id
     * @param FileRepository $fileRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id, FileRepository $fileRepository)
    {
        $file = $fileRepository->getFile($id);
        $file->delete();

        return response()->json([], 200);
    }

}