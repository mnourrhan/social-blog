<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_user_can_successfully_register()
    {
        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
            'name' => 'nourhan',
        ];

        $response = $this->json('POST', route('api.register'), $data);
        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
    }


    /** @test */
    public function it_user_register_with_exist_email()
    {

        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
            'name' =>'nourhan',
        ];

        //register with the same email multiple times
        $this->json('POST',route('api.register'),$data);
        $response = $this->json('POST',route('api.register'),$data);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors',$response->json());
    }

    /** @test */
    public function it_user_register_with_empty_email()
    {

        $data = [
            'email' => '',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
            'name' =>'nourhan',
        ];

        //register with empty email
        $response = $this->json('POST',route('api.register'),$data);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors',$response->json());
    }

    /** @test */
    public function it_user_register_with_birth_date()
    {

        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '',
            'name' =>'nourhan',
        ];

        //register with empty email
        $response = $this->json('POST',route('api.register'),$data);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors',$response->json());
    }

    /** @test */
    public function it_user_register_and_age_set()
    {

        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
            'name' =>'nourhan',
        ];

        $response = $this->json('POST', route('api.register'), $data);
        $response->assertStatus(200);
        $user = User::where('email', 'test@gmail.com')->first();
        $this->assertNotNull($user->age);
    }

    /** @test */
    public function it_user_can_successfully_login()
    {
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

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());

    }

    /** @test */
    public function it_require_email_password_when_login()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);

        $response = $this->json('POST',route('api.login'),[
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('errors', $response->json());

    }
}
