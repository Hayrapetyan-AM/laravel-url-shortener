<?php

namespace App\Services;

use App\Exceptions\LinkNotFoundException;
use App\Models\Link;
use App\Models\User;
use App\Repositories\Contracts\ClickRepositoryInterface;
use App\Repositories\Contracts\LinkRepositoryInterface;

final class RedirectService
{
    public function __construct(
        private readonly LinkRepositoryInterface $links,
        private readonly ClickRepositoryInterface $clicks,
    ) {}

    public function resolve(string $shortCode, string $ipAddress): string
    {
        $link = $this->links->findActiveByShortCode($shortCode);

        if ($link === null) {
            throw LinkNotFoundException::forShortCode($shortCode);
        }

        $this->clicks->record($link, $ipAddress);

        return $link->original_url;
    }

    public function statsForUserLink(User $user, int $linkId): array
    {
        $link = $this->links->findForUser($user, $linkId);

        if ($link === null) {
            throw LinkNotFoundException::forShortCode((string) $linkId);
        }

        return [
            'link' => $link,
            'total_clicks' => $this->clicks->countForLink($link),
            'clicks' => $this->clicks->paginateForLink($link),
        ];
    }
}
