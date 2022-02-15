<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function test(): void
    {
        $environment = new Environment();

        self::assertSame('testing', (string) $environment);

        self::assertTrue(
            $environment->isEqualTo('testing'),
        );

        self::assertFalse(
            $environment->isEqualTo('foo'),
        );

        self::assertTrue(
            $environment->isNotEqualTo('foo'),
        );

        self::assertFalse(
            $environment->isNotEqualTo('testing'),
        );
    }
}
