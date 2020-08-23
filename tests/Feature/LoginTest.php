<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //public function testExample()
    //{
    //   $response = $this->get('/');
	//	$response->assertStatus(200);
    //}

	
	public function testMakeAccountLocked(){
		$testThisData = ['email' => 'backend@multisyscorp.com', 'password' => 'test1234'];
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJson([
                'message' => 'Invalid credentials',
            ])
            ->assertStatus(401);
        $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json'])
            ->assertJsonStructure([
                'message',
				'errors' => [
					'email'
				]
            ])
            ->assertStatus(429);
	}
	
	public function testIfSuccessfulLogin()
    {
		$testThisData = ['email' => 'backend@multisyscorp.com', 'password' => 'test123'];

    $response = $this->json('POST', 'login', $testThisData, ['Accept' => 'application/json']);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token'
        ]);
    }
}
