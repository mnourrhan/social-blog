<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * The maximum number of attempts to allow.
     *
     * @return int
     */
    protected $maxAttempts = 5;


    /**
     * The number of minutes to throttle for.
     *
     * @return int
     */
    protected $decayMinutes = 5;

    protected $model;

    public function __construct(User $user)
    {
        // set the model
        $this->model = new Repository($user);
    }


    public function register(UserRegisterRequest $request)
    {
        try {
            $data = $request->all();
            $validated = $request->validated();
            if($validated) {
                $image_name = $this->save_user_image($request);
                $data['image_name'] = $image_name;

                $user = $this->model->create($data);

                $token = auth()->login($user);

                return $this->respond_with_token($token);
            }
        }catch(\Exception $ex){
            Log::info("Error occurred while registering");
            Log::info($ex);
            return jsend_fail(['message' => 'The data you have entered is invalid!']);
        }
    }

    public function login(UserLoginRequest $request)
    {
        $validated = $request->validated();
        if($validated) {

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $credentials = $request->only(['email', 'password']);
            if (!$token = auth()->attempt($credentials)) {
                $this->incrementLoginAttempts($request);
                return jsend_fail(['message' => 'Incorrect username or password']);
            }
            $this->clearLoginAttempts($request);
            return $this->respond_with_token($token);
        }
    }

    protected function save_user_image($request){
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $image_name = $image->getFilename() . '-' . time().'.'.$extension;
            $image->move(public_path("/images/users/"), $image_name);
            return $image_name;
        }
        return 'default.jpg';
    }

    protected function respond_with_token($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return jsend_success($data);
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return jsend_fail([
            $this->username() => [Lang::get('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ]);
    }
}
