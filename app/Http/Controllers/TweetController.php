<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetStoreRequest;
use App\Models\tweet;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Log;

class TweetController extends Controller
{

    protected $model;

    public function __construct(Tweet $tweet)
    {
        // set the model
        $this->model = new Repository($tweet);
    }

    public function show()
    {
        $following = auth()->user()->followings()->get()->pluck('followed_id')->toArray();
        $following[] = auth()->user()->id;
        $tweets = Tweet::whereIn('user_id',$following)->orderBy('created_at','desc')->paginate(7);
        return jsend_success($tweets);
    }

    public function store(TweetStoreRequest $request)
    {
        $data = $request->all();
        $validated = $request->validated();
        if ($validated) {
            $data['user_id'] = auth()->user()->id;
            $tweet = $this->model->create($data);
            return jsend_success($tweet);
        }
    }

    public function delete($id)
    {
        $tweet = $this->model->find($id);
        if ($tweet) {
            if (auth()->user()->id == $tweet->user_id) {
                $this->model->delete($id);
                return jsend_success(['message' => 'Tweet successfully deleted!']);
            } else {
                return jsend_fail(['message' => 'Permission Denied! You are not authorized to preform this action.']);
            }
        }
        return jsend_fail(['message' => 'The tweet your are trying to delete not exist!']);
    }
}
