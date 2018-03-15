<?php

namespace App\Http\Controllers;

use App\Repositories\TripRepository;
use App\Services\AuthService;
use App\Services\DistanceService;
use App\Services\TripService;
use App\Trip;
use Illuminate\Http\Request;

/**
 * Class TripController
 * @package App\Http\Controllers
 */
class TripController extends Controller
{

    /**
     * @param $hash
     * @param TripRepository $tripRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function single($hash, TripRepository $tripRepository)
    {
        $trip = $tripRepository->getTripByHash($hash);

        return view('trip.single', [
            'trip' => $trip,
            'hash' => $trip->hash
        ]);
    }

    /**
     * @param TripRepository $tripRepository
     * @param TripService $tripService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TripRepository $tripRepository, TripService $tripService)
    {
        $trips = $tripRepository->getTripsForUser();

        foreach ($trips as $trip) {
            $trip->miles = $tripService->getTripMiles($trip);
            $trip->countries = $tripService->getCountryCountForTrip($trip);
        }

        return view('trip.index', [
            'trips' => $trips
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('trip.create');
    }

    /**
     * @param Request $request
     * @param TripRepository $tripRepository
     * @param TripService $tripService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, TripRepository $tripRepository, TripService $tripService)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $trip = $tripRepository->createTrip($request->all());
        $tripService->generateHash($trip);

        return redirect()->route('trip.index');
    }

    /**
     * @param $id
     * @param TripRepository $tripRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, TripRepository $tripRepository)
    {
        $trip = $tripRepository->getTrip($id);
        $tripRepository->deleteTrip($trip);

        return redirect()->route('trip.index');
    }

}
