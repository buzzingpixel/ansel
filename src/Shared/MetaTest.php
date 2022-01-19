<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function test(): void
    {
        $metaEe = new Meta(
            'ee',
            'fooVersion',
        );

        self::assertSame('fooVersion', $metaEe->version());

        self::assertSame('Ansel', $metaEe->name());

        self::assertSame('ansel', $metaEe->shortName());

        self::assertSame(
            'Crop images with pre-defined constraints.',
            $metaEe->description(),
        );

        self::assertSame('TJ Draper', $metaEe->author());

        self::assertSame(
            'https://www.buzzingpixel.com',
            $metaEe->authorUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-ee/documentation',
            $metaEe->docsUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-ee',
            $metaEe->softwarePageLink(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/account',
            $metaEe->buzzingPixelAccountUrl(),
        );

        $metaCraft = new Meta(
            'craft',
            'fooVersion',
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-craft/documentation',
            $metaCraft->docsUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-craft',
            $metaCraft->softwarePageLink(),
        );

        $metaNone = new Meta(
            '',
            'fooVersion',
        );

        self::assertSame('', $metaNone->docsUrl());

        self::assertSame('', $metaNone->softwarePageLink());
    }
}
