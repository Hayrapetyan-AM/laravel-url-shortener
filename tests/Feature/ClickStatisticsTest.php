<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\LinkClick;
use App\Models\User;
use App\Services\RedirectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClickStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_stores_ip_address_and_clicked_at(): void
    {
        $link = Link::factory()->create(['short_code' => 'track9']);

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.42'])
            ->get('/track9')
            ->assertRedirect();

        $click = LinkClick::query()->where('link_id', $link->id)->first();

        $this->assertNotNull($click);
        $this->assertSame('198.51.100.42', $click->ip_address);
        $this->assertNotNull($click->clicked_at);
    }

    public function test_user_can_view_total_clicks_and_click_list_for_own_link(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->for($user)->create();

        LinkClick::factory()->for($link)->create([
            'ip_address' => '203.0.113.1',
            'clicked_at' => now()->subHour(),
        ]);

        LinkClick::factory()->for($link)->create([
            'ip_address' => '203.0.113.2',
            'clicked_at' => now(),
        ]);

        $stats = app(RedirectService::class)->statsForUserLink($user, $link->id);

        $this->assertSame(2, $stats['total_clicks']);
        $this->assertCount(2, $stats['clicks']->items());
        $this->assertSame('203.0.113.2', $stats['clicks']->first()->ip_address);
    }

    public function test_user_cannot_view_statistics_for_another_users_link(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $link = Link::factory()->for($owner)->create();

        $this->expectException(\App\Exceptions\LinkNotFoundException::class);

        app(RedirectService::class)->statsForUserLink($intruder, $link->id);
    }

    public function test_each_redirect_creates_separate_click_record(): void
    {
        $link = Link::factory()->create(['short_code' => 'multi1']);

        $this->get('/multi1');
        $this->get('/multi1');

        $this->assertSame(2, LinkClick::query()->where('link_id', $link->id)->count());
    }
}
