<?php

namespace App\Repositories\Contracts;

use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClickRepositoryInterface
{
    public function record(Link $link, string $ipAddress): LinkClick;

    public function countForLink(Link $link): int;

    public function paginateForLink(Link $link, int $perPage = 20): LengthAwarePaginator;
}
