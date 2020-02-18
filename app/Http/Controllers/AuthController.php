<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{

    // space that we can use the repository from
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
            return $validated;
        }catch(\Exception $ex){
            Log::info("Error occurred while registering");
            Log::info($ex);
            return response()->json(['error' => 'Invalid data'], 500);
        }
    }

    public function login(UserLoginRequest $request)
    {
        $validated = $request->validated();
        if($validated) {
            $credentials = $request->only(['email', 'password']);
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respond_with_token($token);
        }
        return $validated;
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
