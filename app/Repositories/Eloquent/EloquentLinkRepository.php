<?php

namespace App\Repositories\Eloquent;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use App\Repositories\Contracts\LinkRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class EloquentLinkRepository implements LinkRepositoryInterface
{
    public function create(?User $user, string $originalUrl, string $shortCode): Link
    {
        return Link::query()->create([
            'user_id' => $user?->id,
            'original_url' => $originalUrl,
            'short_code' => $shortCode,
            'status' => LinkStatus::Active,
        ]);
    }

    public function findActiveByShortCode(string $shortCode): ?Link
    {
        return Link::query()
            ->where('short_code', $shortCode)
            ->where('status', LinkStatus::Active)
            ->first();
    }

    public function findForUser(User $user, int $linkId): ?Link
    {
        return $user->links()->whereKey($linkId)->first();
    }

    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->links()
            ->withCount('clicks')
            ->latest()
            ->paginate($perPage);
    }

    public function allForUser(User $user): Collection
    {
        return $user->links()
            ->withCount('clicks')
            ->latest()
            ->get();
    }

    public function shortCodeExists(string $shortCode): bool
    {
        return Link::query()->where('short_code', $shortCode)->exists();
    }

    public function delete(Link $link): void
    {
        $link->delete();
    }
}
