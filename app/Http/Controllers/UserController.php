<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Validator;
use App\Jobs\SendEmail;
use App\Mail\EmailQueue;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
	use ThrottlesLogins;
	
	 /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
	 protected $maxAttempts = 5;
		protected $decayMinutes = 5;
    public function login(Request $request){
		
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
			if ($this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				return $this->sendLockoutResponse($request);
			}else{
				$this->incrementLoginAttempts($request);
				return response()->json($validator->errors(), 422);
			}
        }

        if (! $token = auth()->attempt($validator->validated())) {
			if ($this->hasTooManyLoginAttempts($request)) {
				$this->fireLockoutEvent($request);
				return $this->sendLockoutResponse($request);
			}else{
				$this->incrementLoginAttempts($request);
				return response()->json(['message' => 'Invalid credentials'], 401);
			}
        }
		
		$this->clearLoginAttempts($request);
        return $this->createNewToken($token);
    }
	
	/**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
			$fetch = $validator->errors();
			$errorMessage = $fetch->first('email');
			if($errorMessage == "The email has already been taken."){
				$message = "Email already taken.";
			}else{
				$message = $errorMessage;
			}
			
			return response()->json(['message' => $message], 400);
        }

		$userEmail = $request->get('email');
		$userData = [
			'email' => $request->get('email')
		];
        $emailJob = (new SendEmail($userData))->delay(Carbon::now()->addMinutes(2));
        dispatch($emailJob);
		
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
        ));
		
        return response()->json([
            'message' => 'User successfully registered'
        ], 201);
    }
	
	public function username()
    {
        return 'email';
    }
	
	/**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token
        ]);
    }
}
