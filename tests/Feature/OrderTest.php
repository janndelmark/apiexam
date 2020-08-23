<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
   //public function testExample()
   // {
   //     $response = $this->get('/');

   //     $response->assertStatus(200);
   // }
    public function actingAs($user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);
        
        return $this;
    }
   public function testIfSuccessfulOrder(){
		$customer = factory(User::class)->create();
        $this->actingAs($customer, 'api');
		
		$testThisData = ['product_id' => '1', 'quantity' => '5'];
		
		$response = $this->json('POST', 'order', $testThisData, ['Accept' => 'application/json']);
		
		$response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'You have successfully ordered this product'
        ]);
   }
   
   public function testQuantityExceededStockOnHold(){
		$customer = factory(User::class)->create();
        $this->actingAs($customer, 'api');
		
		$testThisData = ['product_id' => '3', 'quantity' => '500'];
		
		$response = $this->json('POST', 'order', $testThisData, ['Accept' => 'application/json']);
		
		$response
        ->assertStatus(400)
        ->assertJson([
            'message' => 'Failed to order this product due to unavailability of the stock'
        ]);
   }
}
