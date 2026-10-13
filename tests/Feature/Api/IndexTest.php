<?php

namespace Tests\Feature\Api;

use App\Actions\Api\CreateApi;
use App\ApiMethod;
use App\CheckStatus;
use App\Livewire\Api\Index;
use App\Models\Api;
use App\Models\Check;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('apis.index'))->assertRedirect(route('login'));
    }

    public function test_verified_users_can_view_the_index(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('apis.index'))
            ->assertOk();
    }

    public function test_api_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('openCreateModal')
            ->set('form.name', 'Stripe API')
            ->set('form.url', 'https://api.stripe.com/health')
            ->set('form.method', ApiMethod::GET->value)
            ->set('form.expected_status', 200)
            ->set('form.timeout', 10)
            ->set('form.is_active', true)
            ->call('createApi')
            ->assertHasNoErrors()
            ->assertSet('creatingApi', false);

        $this->assertDatabaseHas('apis', [
            'user_id' => $user->id,
            'name' => 'Stripe API',
            'url' => 'https://api.stripe.com/health',
            'method' => ApiMethod::GET->value,
            'expected_status' => 200,
            'timeout' => 10,
        ]);
    }

    public function test_invalid_input_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('openCreateModal')
            ->set('form.name', '')
            ->set('form.url', 'not-a-url')
            ->set('form.method', 'INVALID')
            ->set('form.expected_status', 999)
            ->set('form.timeout', 0)
            ->call('createApi')
            ->assertHasErrors(['form.name', 'form.url', 'form.method', 'form.expected_status', 'form.timeout']);

        $this->assertDatabaseCount('apis', 0);
    }

    public function test_creation_stays_bound_to_the_authenticated_user(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $api = (new CreateApi)->handle($owner, [
            'name' => 'Stripe API',
            'url' => 'https://api.stripe.com/health',
            'method' => ApiMethod::GET->value,
            'expected_status' => 200,
            'timeout' => 10,
            'description' => null,
            'is_active' => true,
            'user_id' => $other->id,
        ]);

        $this->assertSame($owner->id, $api->user_id);
        $this->assertDatabaseHas('apis', [
            'id' => $api->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_search_filters_apis_by_name_or_url(): void
    {
        $user = User::factory()->create();

        Api::factory()->for($user)->create(['name' => 'Stripe Gateway', 'url' => 'https://api.stripe.com']);
        Api::factory()->for($user)->create(['name' => 'GitHub Status', 'url' => 'https://api.github.com']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('search', 'stripe')
            ->assertSee('Stripe Gateway')
            ->assertDontSee('GitHub Status');
    }

    public function test_method_filter_only_shows_matching_apis(): void
    {
        $user = User::factory()->create();

        Api::factory()->for($user)->create(['name' => 'Read Endpoint', 'method' => ApiMethod::GET]);
        Api::factory()->for($user)->create(['name' => 'Write Endpoint', 'method' => ApiMethod::POST]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('method', ApiMethod::POST->value)
            ->assertSee('Write Endpoint')
            ->assertDontSee('Read Endpoint');
    }

    public function test_status_filter_uses_the_latest_check_status(): void
    {
        $user = User::factory()->create();

        $online = Api::factory()->for($user)->create(['name' => 'Healthy Endpoint']);
        Check::factory()->for($online)->create([
            'status' => CheckStatus::ONLINE,
            'status_code' => 200,
            'checked_at' => now(),
        ]);

        $offline = Api::factory()->for($user)->create(['name' => 'Broken Endpoint']);
        Check::factory()->for($offline)->create([
            'status' => CheckStatus::OFFLINE,
            'status_code' => 503,
            'checked_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('status', CheckStatus::ONLINE->value)
            ->assertSee('Healthy Endpoint')
            ->assertDontSee('Broken Endpoint');
    }

    public function test_changing_filters_resets_pagination(): void
    {
        $user = User::factory()->create();
        Api::factory()->for($user)->count(10)->create(['name' => 'Monitored']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('gotoPage', 2)
            ->assertSet('paginators.page', 2)
            ->set('search', 'Monitored')
            ->assertSet('paginators.page', 1);
    }
}
