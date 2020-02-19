<?php

namespace Tests\Feature;

use App\Models\User;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginAttemptsTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;

    /** @test */
    public function it_user_locked_after_5_login_wrong_attempts()
    {
        $user = factory(User::class)->create([
            'name' => 'test',
            'email'=>'testing@gmail.com',
            'password' => 'secret1234',
            'birth_date' => '1990-10-09',
        ]);
        foreach(range(0,5) as $index) {
            $response = $this->json('POST',route('api.login'),[
                'email' => 'testing@gmail.com',
                'password' => 'secret123',
            ]);
        }
        $response->assertStatus(400);
        $this->assertArraySubset(['status' => 'fail'], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }
}
