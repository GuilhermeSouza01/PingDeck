<?php

namespace Tests\Feature\Api;

use App\Models\Api;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $api = Api::factory()->create();

        $this->get(route('apis.show', $api))->assertRedirect(route('login'));
    }

    public function test_owner_can_view_the_api(): void
    {
        $owner = User::factory()->create();
        $api = Api::factory()->for($owner)->create();

        $this->actingAs($owner)
            ->get(route('apis.show', $api))
            ->assertOk();
    }

    public function test_other_users_are_forbidden(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $api = Api::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('apis.show', $api))
            ->assertForbidden();
    }
}
