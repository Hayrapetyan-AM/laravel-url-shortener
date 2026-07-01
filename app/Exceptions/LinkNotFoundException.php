<?php

namespace App\Exceptions;

use RuntimeException;

final class LinkNotFoundException extends RuntimeException
{
    public static function forShortCode(string $shortCode): self
    {
        return new self("Link with code [{$shortCode}] was not found.");
    }
}
