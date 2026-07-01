<?php

namespace App\Contracts;

interface ShortCodeGeneratorInterface
{
    public function generate(): string;
}
