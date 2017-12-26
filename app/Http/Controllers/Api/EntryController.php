<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Location;
use App\Repositories\EntryLocationRepository;
use App\Repositories\EntryRepository;
use App\Repositories\FileRepository;
use App\Repositories\ImageRepository;
use App\Repositories\LocationRepository;
use App\Services\EntryLocationService;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EntryController
 * @package App\Http\Controllers\Api
 */
class EntryController extends Controller {

    /**
     * @param Request $request
     */
    public function store(
        Request $request,
        EntryRepository $entryRepository,
        FileService $fileService,
        EntryLocationService $entryLocationService
    ) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $data = $request->all();
        $entry = $entryRepository->createEntry($data);
        $fileService->attachFilesToEntry($entry, $data['files']);
        $entryLocationService->attachLocationsToEntry($entry, $data['locations']);

        return response()->json($entry, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param EntryRepository $entryRepository
     * @param FileService $fileService
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Request $request,
        $id,
        EntryRepository $entryRepository,
        FileService $fileService,
        EntryLocationService $entryLocationService
    ) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        
        $data = $request->all();
        $entry = $entryRepository->getEntry($id);
        $entry = $entryRepository->updateEntry($entry, $data);
        $fileService->attachFilesToEntry($entry, $data['files']);
        $entryLocationService->attachLocationsToEntry($entry, $data['locations']);

        return response()->json($entry, 200);
    }


}