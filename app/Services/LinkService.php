<?php

namespace App\Services;

use App\Contracts\ShortCodeGeneratorInterface;
use App\Exceptions\LinkNotFoundException;
use App\Models\Link;
use App\Models\User;
use App\Repositories\Contracts\ClickRepositoryInterface;
use App\Repositories\Contracts\LinkRepositoryInterface;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class LinkService
{
    private const int MAX_CODE_ATTEMPTS = 10;

    public function __construct(
        private readonly LinkRepositoryInterface $links,
        private readonly ShortCodeGeneratorInterface $shortCodeGenerator,
    ) {}

    public function create(?User $user, string $originalUrl): Link
    {
        $shortCode = $this->generateUniqueShortCode();

        return $this->links->create($user, $originalUrl, $shortCode);
    }

    public function deleteForUser(User $user, int $linkId): void
    {
        $link = $this->links->findForUser($user, $linkId);

        if ($link === null) {
            throw LinkNotFoundException::forShortCode((string) $linkId);
        }

        $this->links->delete($link);
    }

    private function generateUniqueShortCode(): string
    {
        for ($attempt = 0; $attempt < self::MAX_CODE_ATTEMPTS; $attempt++) {
            $code = $this->shortCodeGenerator->generate();

            if (! $this->links->shortCodeExists($code)) {
                return $code;
            }
        }

        $exception = new RuntimeException('Unable to generate a unique short code.');

        Log::error($exception->getMessage(), ['exception' => $exception]);

        throw $exception;
    }
}
