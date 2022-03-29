<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

class UuidBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        return [UuidFactoryInterface::class => UuidFactory::class];
    }
}
