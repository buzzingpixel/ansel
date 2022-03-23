<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use DateTimeImmutable;
use DateTimeZone;

class SystemClock implements ClockContract
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC'),
        );
    }
}
