<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use App\Services\LinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_link(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $link = app(LinkService::class)->create($user, 'https://laravel.com');

        $this->assertDatabaseHas('links', [
            'id' => $link->id,
            'user_id' => $user->id,
            'original_url' => 'https://laravel.com',
        ]);

        $this->assertNotEmpty($link->short_code);
    }

    public function test_user_only_sees_own_links_in_repository(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownLink = Link::factory()->for($user)->create();
        Link::factory()->for($otherUser)->create();

        $links = app(\App\Repositories\Contracts\LinkRepositoryInterface::class)
            ->allForUser($user);

        $this->assertCount(1, $links);
        $this->assertTrue($links->first()->is($ownLink));
    }

    public function test_deleting_link_removes_clicks(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->for($user)->create();

        $link->clicks()->create([
            'ip_address' => '127.0.0.1',
            'clicked_at' => now(),
        ]);

        app(LinkService::class)->deleteForUser($user, $link->id);

        $this->assertDatabaseCount('link_clicks', 0);
        $this->assertDatabaseCount('links', 0);
    }
}
