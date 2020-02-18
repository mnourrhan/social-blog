<?php

namespace Tests\Feature;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(){
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $response = $this->json('POST',route('api.login'),[
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);


        return $response['access_token'];
    }

    /** @test */
    public function authenticated_user_can_delete_his_tweet()
    {
        $token = $this->authenticate();
        $tweet = factory(Tweet::class)->create([
            'content' => 'My first tweet for testing!',
            'user_id' => auth()->user()->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('tweet.delete', $tweet->id));

        $response->assertStatus(200);
        $this->assertArrayHasKey('success', $response->json());
    }

    /** @test */
    public function unauthenticated_user_cant_delete_tweet()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $tweet = factory(Tweet::class)->create([
            'content' => 'My first tweet for testing!',
            'user_id' => $user->id
        ]);

        $response = $this->json('POST',route('tweet.delete', $tweet->id));

        $response->assertStatus(401);
        $this->assertArrayHasKey('message', $response->json());
    }

    /** @test */
    public function user_cant_delete_other_user_tweet()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test1@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $tweet = factory(Tweet::class)->create([
            'content' => 'My first tweet for testing!',
            'user_id' => $user->id
        ]);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('tweet.delete', $tweet->id));

        $response->assertStatus(402);
        $this->assertArrayHasKey('errors', $response->json());
    }

}
