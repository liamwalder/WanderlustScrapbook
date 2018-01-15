<?php

namespace App\Services;

use App\Entry;
use App\Location;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService {

    /**
     * @return mixed
     */
    public function getUser()
    {
        return Auth::user();
    }


}