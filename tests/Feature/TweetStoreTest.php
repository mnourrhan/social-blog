<?php

namespace Tests\Feature;

use App\Models\User;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetStoreTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;

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

        return $response['data']['access_token'];
    }

    /** @test */
    public function authenticated_users_can_create_tweet()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('tweet.create'),[
            'content' => 'My first tweet for testing!'
        ]);
        $response->assertStatus(200);
        $this->assertArraySubset(['status' => 'success'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function unauthenticated_users_cant_create_tweet()
    {

        $response = $this->json('POST',route('tweet.create'),[
            'content' => 'My first tweet for testing!'
        ]);
        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function tweet_should_not_exceed_140_char()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('tweet.create'),[
            'content' => str_repeat('a', 141)
        ]);
        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }
}
