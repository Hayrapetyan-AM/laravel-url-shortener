<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use App\Services\LinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_shorten_url_from_homepage(): void
    {
        $response = $this->post(route('links.store'), [
            'original_url' => 'https://example.com/article',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('createdShortUrl');

        $this->assertDatabaseHas('links', [
            'original_url' => 'https://example.com/article',
            'user_id' => null,
        ]);
    }

    public function test_guest_link_can_be_redirected(): void
    {
        $link = app(LinkService::class)->create(null, 'https://example.com/target');

        $this->get('/'.$link->short_code)
            ->assertRedirect('https://example.com/target');
    }

    public function test_authenticated_user_gets_link_assigned_on_homepage(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('links.store'), [
                'original_url' => 'https://example.com/owned',
            ])
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('links', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com/owned',
        ]);
    }
}
