<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
use App\Repositories\LocationRepository;
use App\Services\FileService;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MediaController
 * @package App\Http\Controllers\Api
 */
class MediaController extends Controller {

    /**
     * @param Request $request
     */
    public function store(Request $request, MediaService $mediaService)
    {
        $mediaFiles = [];

        foreach ($request->files->all() as $file) {
            $extension = $file->getClientOriginalExtension();

            $filename = md5($file->getFilename().time());

            Storage::disk('local')->put($filename.'.'.$extension,  File::get($file));

            $mediaFile = new \App\File();

            $mediaFile->mime = $file->getClientMimeType();
            $mediaFile->filename =  url('/') . '/' . $filename.'.'.$extension;
            $mediaFile->original_filename = $file->getClientOriginalName();
            $mediaFile->thumbnail = $mediaService->generateThumbnail($mediaFile);
            
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


    /**
     * @param Request $request
     * @param LocationRepository $locationRepository
     * @param FileService $fileService
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachToLocation(Request $request, LocationRepository $locationRepository, FileService $fileService)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $location = $locationRepository->getLocation($request->get('location'));
        $fileService->attachFilesToLocation($location, $request->get('files'));

        return response()->json([], 200);
    }




}