<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetStoreRequest;
use App\Models\tweet;
use App\Repositories\Repository;

class TweetController extends Controller
{

    protected $model;

    public function __construct(Tweet $tweet)
    {
        // set the model
        $this->model = new Repository($tweet);
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
                return jsend_fail(['errors' => 'Permission Denied! You are not authorized to preform this action.']);
            }
        }
        return jsend_fail(['errors' => 'The tweet your are trying to delete not exist!']);
    }
}
