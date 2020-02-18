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

    public function delete($id){
        $tweet = $this->model->find($id);
        if(auth()->user()->id == $tweet->user_id){
            $this->model->delete($id);
            return response()->json(['success' =>  'Tweet successfully deleted!', 'code' => 200], 200);
        }else {
            return response()->json(['errors' =>  'Permission Denied! You are not authorized to preform this action.',
                'code' => 402], 402);
        }
    }
}
