<?php
/**
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2020.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\BaseResponseTraits;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessTokenController extends Controller
{
    use BaseResponseTraits, AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 60;

    public function register(Request $req){
        try {
            DB::beginTransaction();
            $user = User::storeExec($req);
            if(is_string($user)){
                throw new \Exception($user);
            }
            DB::commit();
            return $this->internalRes(201, $user, 'Succeed to create register');
        }catch (\Exception $e){
            return $this->internalRes(400, $e->getMessage());
        }
    }

    public function login(Request $request, RateLimiter $limiter){
        try {
            //check if the max number of login attempts has been reached
            if ($this->hasTooManyLoginAttempts($request))
            {
                $this->fireLockoutEvent($request);

                return $this->internalRes(403, 'Unauthorized, To many attempts, \''.$request['email'].'\' has been blocked please contact our administrator');
            }

            $credentials = request(['email', 'password']);

            if (Auth::attempt($credentials))
            {
                //reset failed login attemps
                $this->clearLoginAttempts($request);
            } else {
                //count user failed login attempts
                $this->incrementLoginAttempts($request);
                $keyLimiter = $this->throttleKey($request);
                $chance = $limiter->retriesLeft($keyLimiter, $this->maxAttempts);

                return $this->internalRes(401, 'Unauthorized, '.$chance.' chances left');
            }

            $user = $request->user();
            $user->oauthAccessTokens()->delete();
            $tokenResult = $user->createToken('MaarifComp');

            switch ($user->role){
                case 0;
                    $name = 'Super Admin';
                    $portal = 'super admin';
                    break;
                case 1;
                    $name = $user->admin->name;
                    $portal = 'admin';
                    $company = $user->company;
                    break;
                default:
//                    $name = $user->customer->name;
                    $name = 'DUmmy';
                    $portal = 'customer';
                    $company = [];
            }

            $data = [
                'user' => [
                    'email' => $user->email,
                    'name' => $name,
                    'portal' => $portal,
                    'join_at' => $user->created_at,
                    'company' => $company,
                ],
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
            ];

            return $this->internalRes(200, $data);
        }catch (\Exception $e){
            return $this->internalRes(400, $e->getMessage());
        }
    }
}
