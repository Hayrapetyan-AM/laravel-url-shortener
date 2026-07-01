<?php

namespace App\Repositories\Contracts;

use App\Models\Link;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LinkRepositoryInterface
{
    public function create(?User $user, string $originalUrl, string $shortCode): Link;

    public function findActiveByShortCode(string $shortCode): ?Link;

    public function findForUser(User $user, int $linkId): ?Link;

    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator;

    public function allForUser(User $user): Collection;

    public function shortCodeExists(string $shortCode): bool;

    public function delete(Link $link): void;
}
