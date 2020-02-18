<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginAttemptsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_user_locked_after_5_login_wrong_attempts()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);
        foreach(range(0,5) as $index) {
            $response = $this->json('POST',route('api.login'),[
                'email' => 'test@gmail.com',
                'password' => 'secret123',
            ]);
        }
        $response->assertStatus(429);
        $this->assertArrayHasKey('errors', $response->json());

    }
}
