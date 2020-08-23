<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class CreateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
	//use RefreshDatabase;
	
	public function testMakeRegistrationSuccessful(){
		$request = ["email" => "backend@multisyscorp.com", "password" => "test123"];
		$response = $this->json('POST', 'register', $request, ['Accept' => 'application/json']);

        $response->assertJson([
                'message' => 'User successfully registered',
            ])->assertStatus(201);
	}
	
	public function testIfDuplicateEmails(){
		$duplicate = ["email" => "backend@multisyscorp.com", "password" => "test123"];
		$duplicateResponse = $this->json('POST', 'register', $duplicate, ['Accept' => 'application/json']);

        $duplicateResponse->assertJson([
                'message' => 'Email already taken.',
            ])->assertStatus(400);
	}
}
