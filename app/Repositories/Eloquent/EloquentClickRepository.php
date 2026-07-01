<?php

namespace App\Repositories\Eloquent;

use App\Models\Link;
use App\Models\LinkClick;
use App\Repositories\Contracts\ClickRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentClickRepository implements ClickRepositoryInterface
{
    public function record(Link $link, string $ipAddress): LinkClick
    {
        return $link->clicks()->create([
            'ip_address' => $ipAddress,
            'clicked_at' => now(),
        ]);
    }

    public function countForLink(Link $link): int
    {
        return $link->clicks()->count();
    }

    public function paginateForLink(Link $link, int $perPage = 20): LengthAwarePaginator
    {
        return $link->clicks()
            ->latest('clicked_at')
            ->paginate($perPage);
    }
}
