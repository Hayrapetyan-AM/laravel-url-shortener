<?php

namespace App\Providers;

use App\Contracts\ShortCodeGeneratorInterface;
use App\Repositories\Contracts\ClickRepositoryInterface;
use App\Repositories\Contracts\LinkRepositoryInterface;
use App\Repositories\Eloquent\EloquentClickRepository;
use App\Repositories\Eloquent\EloquentLinkRepository;
use App\Services\ShortCode\RandomShortCodeGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LinkRepositoryInterface::class, EloquentLinkRepository::class);
        $this->app->bind(ClickRepositoryInterface::class, EloquentClickRepository::class);
        $this->app->bind(ShortCodeGeneratorInterface::class, RandomShortCodeGenerator::class);
    }

    public function boot(): void
    {
        //
    }
}
