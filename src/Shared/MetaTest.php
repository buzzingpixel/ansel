<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function test(): void
    {
        $meta = new Meta('fooVersion');

        self::assertSame('fooVersion', $meta->version());

        self::assertSame('Ansel', $meta->name());

        self::assertSame('ansel', $meta->shortName());

        self::assertSame(
            'Crop images with pre-defined constraints.',
            $meta->description(),
        );

        self::assertSame('TJ Draper', $meta->author());

        self::assertSame(
            'https://www.buzzingpixel.com',
            $meta->authorUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-ee/documentation',
            $meta->eeDocsUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-craft/documentation',
            $meta->craftDocsUrl(),
        );
    }
}
