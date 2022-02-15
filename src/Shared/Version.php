<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

class Version
{
    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return ANSEL_VER;
    }

    public function isEqualTo(string $comparison): bool
    {
        return $comparison === $this->toString();
    }

    public function isNotEqualTo(string $comparison): bool
    {
        return $comparison !== $this->toString();
    }
}
