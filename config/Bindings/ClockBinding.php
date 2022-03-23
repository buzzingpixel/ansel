<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\SystemClock;

class ClockBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        return [ClockContract::class => SystemClock::class];
    }
}
