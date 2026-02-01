<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    // use RefreshDatabase; // Don't wipe the DB for this quick check

    public function test_registration_desk_route_exists()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view-all-patients'); // Assuming this permission exists and is needed

        $response = $this->actingAs($user)->get(route('registration-desk'));

        $response->assertStatus(200);
    }
}
