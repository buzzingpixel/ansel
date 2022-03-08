<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\EE;

use PHPUnit\Framework\TestCase;

class SiteMetaTest extends TestCase
{
    public function test(): void
    {
        self::assertSame(
            456,
            (new SiteMeta(456))->siteId(),
        );
    }
}
