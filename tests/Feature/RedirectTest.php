<?php

namespace Tests\Feature;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_short_link_redirects_to_original_url(): void
    {
        $link = Link::factory()->create([
            'original_url' => 'https://example.com/target',
            'short_code' => 'abc123',
            'status' => LinkStatus::Active,
        ]);

        $response = $this->get('/abc123');

        $response->assertRedirect('https://example.com/target');
    }

    public function test_redirect_records_click_statistics(): void
    {
        $link = Link::factory()->create([
            'short_code' => 'track1',
            'status' => LinkStatus::Active,
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->get('/track1')
            ->assertRedirect();

        $this->assertDatabaseHas('link_clicks', [
            'link_id' => $link->id,
            'ip_address' => '203.0.113.10',
        ]);

        $this->assertSame(1, LinkClick::query()->where('link_id', $link->id)->count());
    }

    public function test_inactive_link_returns_not_found(): void
    {
        Link::factory()->create([
            'short_code' => 'hidden',
            'status' => LinkStatus::Inactive,
        ]);

        $this->get('/hidden')->assertNotFound();
    }

    public function test_unknown_short_code_returns_not_found(): void
    {
        $this->get('/missing')->assertNotFound();
    }
}
