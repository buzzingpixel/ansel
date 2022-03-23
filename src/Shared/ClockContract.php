<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use DateTimeImmutable;

interface ClockContract
{
    /**
     * Returns the current time as a DateTimeImmutable Object
     */
    public function now(): DateTimeImmutable;
}
