<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use PHPUnit\Framework\TestCase;

use function file_get_contents;

class InternalFunctionsTest extends TestCase
{
    public function test(): void
    {
        $path = __DIR__ . '/InternalFunctionTestFile.txt';

        self::assertSame(
            (string) file_get_contents($path),
            (new InternalFunctions())->fileGetContents(
                $path,
            ),
        );
    }
}
