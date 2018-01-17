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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function single($id)
    {
        return view('trip.single', [
            'id' => $id
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
    public function create()
    {
        return view('trip.create');
    }

    /**
     * @param Request $request
     * @param TripRepository $tripRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, TripRepository $tripRepository)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $tripRepository->createTrip($request->all());
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
