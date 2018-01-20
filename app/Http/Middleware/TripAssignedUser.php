<?php

namespace App\Http\Middleware;

use App\Repositories\TripRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;

use Closure;

/**
 * Class TripAssignedUser
 * @package App\Http\Middleware
 */
class TripAssignedUser
{

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @var TripRepository
     */
    protected $tripRepository;

    /**
     * TripAssignedUser constructor.
     * @param AuthService $authService
     * @param TripRepository $tripRepository
     */
    public function __construct(AuthService $authService, TripRepository $tripRepository)
    {
        $this->authService = $authService;
        $this->tripRepository = $tripRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $this->authService->getUser();
        $trip = $this->tripRepository->getTrip($request->id);

        if ($trip->user->id !== $user->id) {
            return redirect()->route('trip.index');
        }

        return $next($request);
    }

}
