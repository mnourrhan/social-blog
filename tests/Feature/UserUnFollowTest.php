<?php

namespace Tests\Feature;

use App\Models\User;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUnFollowTest extends TestCase
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
    public function authenticated_user_can_unfollow_another_user()
    {
        $token = $this->authenticate();

        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test1@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $user->followers()->attach(auth()->user()->id);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('user.unfollow', $user->id));

        $response->assertStatus(200);
        $this->assertArraySubset(['status' => 'success'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function authenticated_user_cant_unfollow_same_user()
    {
        $token = $this->authenticate();

        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test1@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $user->followers()->attach(auth()->user()->id);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('user.unfollow', $user->id));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('user.unfollow', $user->id));

        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function authenticated_user_cant_unfollow_his_account()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('user.unfollow', auth()->user()->id));


        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function authenticated_user_cant_unfollow_not_existing_user()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json',
        ])->json('POST',route('user.unfollow', 66687));

        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function unauthenticated_users_cant_unfollow_another_user()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test1@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $response = $this->json('POST',route('user.unfollow', $user));

        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }
}
