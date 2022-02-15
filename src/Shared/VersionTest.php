<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function test(): void
    {
        $version = new Version();

        self::assertSame('9.8.7', (string) $version);

        self::assertTrue(
            $version->isEqualTo('9.8.7'),
        );

        self::assertFalse(
            $version->isEqualTo('foo'),
        );

        self::assertTrue(
            $version->isNotEqualTo('foo'),
        );

        self::assertFalse(
            $version->isNotEqualTo('9.8.7'),
        );
    }
}
