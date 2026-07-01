<?php

namespace Tests\Unit;

use App\Contracts\ShortCodeGeneratorInterface;
use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use App\Repositories\Contracts\LinkRepositoryInterface;
use App\Services\LinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LinkServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_anonymous_short_link(): void
    {
        $generator = Mockery::mock(ShortCodeGeneratorInterface::class);
        $generator->shouldReceive('generate')->once()->andReturn('guest1');

        $this->app->instance(ShortCodeGeneratorInterface::class, $generator);

        $link = app(LinkService::class)->create(null, 'https://example.com/page');

        $this->assertDatabaseHas('links', [
            'user_id' => null,
            'original_url' => 'https://example.com/page',
            'short_code' => 'guest1',
            'status' => LinkStatus::Active->value,
        ]);
    }

    public function test_it_creates_a_unique_short_link_for_user(): void
    {
        $user = User::factory()->create();

        $generator = Mockery::mock(ShortCodeGeneratorInterface::class);
        $generator->shouldReceive('generate')->once()->andReturn('abc123');

        $this->app->instance(ShortCodeGeneratorInterface::class, $generator);

        $link = app(LinkService::class)->create($user, 'https://example.com/page');

        $this->assertDatabaseHas('links', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com/page',
            'short_code' => 'abc123',
            'status' => LinkStatus::Active->value,
        ]);

        $this->assertSame('abc123', $link->short_code);
    }

    public function test_it_retries_when_short_code_already_exists(): void
    {
        $user = User::factory()->create();

        Link::factory()->create([
            'user_id' => $user->id,
            'short_code' => 'taken1',
        ]);

        $generator = Mockery::mock(ShortCodeGeneratorInterface::class);
        $generator->shouldReceive('generate')->twice()->andReturn('taken1', 'free12');

        $this->app->instance(ShortCodeGeneratorInterface::class, $generator);

        $link = app(LinkService::class)->create($user, 'https://example.com');

        $this->assertSame('free12', $link->short_code);
    }

    public function test_it_deletes_link_for_owner(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->for($user)->create();

        app(LinkService::class)->deleteForUser($user, $link->id);

        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }
}
