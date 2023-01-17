<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyOrdersUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_an_order_status()
    {
        $user = User::factory()->create(['role' => 'delivery']);
        Order::factory()->create([
            'id' => 55,
            'delivery_user_id' => $user->id,
            'status' => 'assigned delivery',
        ]);
        Sanctum::actingAs(
            $user,
            ['orders:update']
        );

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => 'in establishment',
        ]);

        $order = Order::find(55);
        $response->assertStatus(200);
        $this->assertEquals('in establishment', $order->status);
    }

    /** @test */
    public function status_is_required()
    {
        $user = User::factory()->create(['role' => 'delivery']);
        Order::factory()->create([
            'id' => 55,
            'delivery_user_id' => $user->id,
            'status' => 'assigned delivery',
        ]);
        Sanctum::actingAs(
            $user,
            ['orders:update']
        );

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => null,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('status');
    }

    /** @test */
    public function status_should_be_valid()
    {
        $user = User::factory()->create(['role' => 'delivery']);
        Order::factory()->create([
            'id' => 55,
            'delivery_user_id' => $user->id,
            'status' => 'assigned delivery',
        ]);
        Sanctum::actingAs(
            $user,
            ['orders:update']
        );

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => 'invalid-status',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('status');

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => 'delivered',
        ]);
        $response->assertStatus(200);

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => 'in establishment',
        ]);
        $response->assertStatus(200);
    }

    /** @test */
    public function cannot_update_an_order_that_dont_belongs_to_me()
    {
        $user = User::factory()->create(['role' => 'delivery']);
        $anotherUser = User::factory()->create(['role' => 'delivery']);
        Order::factory()->create([
            'id' => 55,
            'delivery_user_id' => $anotherUser->id,
            'status' => 'assigned delivery',
        ]);
        Sanctum::actingAs(
            $user,
            ['orders:update']
        );

        $response = $this->json('PUT', '/api/my-orders/55', [
            'status' => 'invalid-status',
        ]);

        $response->assertStatus(404);
    }
}
