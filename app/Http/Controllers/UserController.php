<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Repository;

class UserController extends Controller
{

    protected $model;

    public function __construct(User $user)
    {
        // set the model
        $this->model = new Repository($user);
    }

    public function follow($id)
    {
        $user = $this->model->find($id);
        if(! $user) {
            return jsend_fail(['message' => 'The user you are trying to follow is not exist!']);
        }elseif(auth()->user()->id == $user->id) {
            return jsend_fail(['message' => 'You can\'t follow your account as you already can see your tweets!']);
        }elseif (auth()->user()->isFollowing($user)) {
            return jsend_fail(['message' => 'You are already following this user!']);
        }else {
            $user->followers()->attach(auth()->user()->id);
            return jsend_success(['message' => 'Successfully followed the user!']);
        }
    }

    public function unfollow(int $profileId)
    {
        $user = User::find($profileId);
        if(! $user) {
            return jsend_fail(['message' => 'You are already following this user!']);
        }else if(auth()->user()->id == $user->id) {
            return jsend_fail(['message' => 'You can\'t unfollow your account as you should see your tweets!']);
        }else if (auth()->user()->isFollowing($user)) {
            $user->followers()->detach(auth()->user()->id);
            return jsend_success(['message' => 'Successfully unfollowed the user!']);
        }else {
            return jsend_fail(['message' => 'You are already not following this user!']);
        }


    }
}
