<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\VerifyUserNotification;
use App\User;
use App\Http\Controllers\Controller;
use App\VerifyUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/trips';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'api_token' => str_random(60),
            'password' => bcrypt($data['password']),
        ]);

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        $user->notify(new VerifyUserNotification($user, $verifyUser));

        return $user;
    }

    /**
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();

        if (isset($verifyUser)) {

            $user = $verifyUser->user;

            $status = "Your e-mail is already verified. You can now login.";
            if (!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
            }

        } else {
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect('/login')->with('success', $status);
    }

    /**
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        return redirect('/login')->with('info', 'We sent you an activation code. Check your email and click on the link to verify. Please check your spam.');
    }

}
