<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetStoreRequest;
use App\Models\tweet;
use App\Repositories\Repository;
use Illuminate\Http\Request;

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
            $tweet = $this->model->create($data);
            return response()->json(['success' =>  'Tweet successfully created!', 'tweet' => $tweet], 200);

        }
        return $validated;
    }
}
