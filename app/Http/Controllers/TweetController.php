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

    public function store(TweetStoreRequest $request){
        $data = $request->all();
        $validated = $request->validated();
        if($validated) {
            $data['user_id'] = auth()->user()->id;
            $user = $this->model->create($data);
            return response()->json(['success' =>  'Tweet successfully created!', 'code' => 200], 200);

        }
        return $validated;
    }

}
