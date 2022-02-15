<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Meta;

use BuzzingPixel\Ansel\Shared\Environment;
use BuzzingPixel\Ansel\Shared\Version;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function test(): void
    {
        $version = new class extends Version {
            public function toString(): string
            {
                return 'fooVersion';
            }
        };

        $eeEnvironment = new class extends Environment {
            public function toString(): string
            {
                return 'ee';
            }
        };

        $metaEe = new Meta(
            $version,
            $eeEnvironment,
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

        $craftEnvironment = new class extends Environment {
            public function toString(): string
            {
                return 'craft';
            }
        };

        $metaCraft = new Meta(
            $version,
            $craftEnvironment,
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-craft/documentation',
            $metaCraft->docsUrl(),
        );

        self::assertSame(
            'https://www.buzzingpixel.com/software/ansel-craft',
            $metaCraft->softwarePageLink(),
        );

        $emptyEnvironment = new class extends Environment {
            public function toString(): string
            {
                return '';
            }
        };

        $metaNone = new Meta(
            $version,
            $emptyEnvironment,
        );

        self::assertSame('', $metaNone->docsUrl());

        self::assertSame('', $metaNone->softwarePageLink());
    }
}
