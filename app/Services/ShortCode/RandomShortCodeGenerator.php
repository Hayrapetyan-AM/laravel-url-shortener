<?php

namespace App\Services\ShortCode;

use App\Contracts\ShortCodeGeneratorInterface;
use Illuminate\Support\Str;

final class RandomShortCodeGenerator implements ShortCodeGeneratorInterface
{
    private const int LENGTH = 6;

    public function generate(): string
    {
        return Str::lower(Str::random(self::LENGTH));
    }
}
