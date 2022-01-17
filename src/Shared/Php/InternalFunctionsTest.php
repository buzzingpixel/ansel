<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function strtotime;

class InternalFunctionsTest extends TestCase
{
    public function testStrToTime(): void
    {
        $time = (new InternalFunctions())->time();

        self::assertSame(
            strtotime(
                '+1 Day',
                $time,
            ),
            (new InternalFunctions())->strToTime(
                '+1 Day',
                $time,
            ),
        );

        self::assertSame(
            strtotime('+1 Day'),
            (new InternalFunctions())->strToTime('+1 Day'),
        );
    }

    public function testFileExists(): void
    {
        self::assertTrue((new InternalFunctions())->fileExists(
            __DIR__ . '/InternalFunctionTestFile.txt',
        ));

        self::assertFalse((new InternalFunctions())->fileExists(
            '/foo/bar/file.txt',
        ));
    }

    public function testFileGetContents(): void
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
