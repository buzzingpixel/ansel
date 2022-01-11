<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use craft\web\twig\TemplateLoader;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\Source;

class CraftTwigLoaderTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private Source $source;

    private CraftTwigLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->source = new Source('', '', '');

        $this->loader = new CraftTwigLoader(
            $this->mockAnselLoader(),
            $this->mockCraftLoader(),
        );
    }

    private function mockAnselLoader(): FilesystemLoader
    {
        $mock = $this->createMock(FilesystemLoader::class);

        $mock->method('getSourceContext')->willReturnCallback(
            function (string $name): Source {
                $this->calls[] = [
                    'object' => 'AnselLoader',
                    'method' => 'getSourceContext',
                    'name' => $name,
                ];

                return $this->source;
            }
        );

        $mock->method('getCacheKey')->willReturnCallback(
            function (string $name): string {
                $this->calls[] = [
                    'object' => 'AnselLoader',
                    'method' => 'getCacheKey',
                    'name' => $name,
                ];

                return 'AnselCacheKey';
            }
        );

        $mock->method('isFresh')->willReturnCallback(
            function (string $name, int $time): bool {
                $this->calls[] = [
                    'object' => 'AnselLoader',
                    'method' => 'isFresh',
                    'name' => $name,
                    'time' => $time,
                ];

                return true;
            }
        );

        $mock->method('exists')->willReturnCallback(
            function (string $name): bool {
                $this->calls[] = [
                    'object' => 'AnselLoader',
                    'method' => 'exists',
                    'name' => $name,
                ];

                return true;
            }
        );

        return $mock;
    }

    private function mockCraftLoader(): TemplateLoader
    {
        $mock = $this->createMock(TemplateLoader::class);

        $mock->method('getSourceContext')->willReturnCallback(
            function (string $name): Source {
                $this->calls[] = [
                    'object' => 'CraftLoader',
                    'method' => 'getSourceContext',
                    'name' => $name,
                ];

                return $this->source;
            }
        );

        $mock->method('getCacheKey')->willReturnCallback(
            function (string $name): string {
                $this->calls[] = [
                    'object' => 'CraftLoader',
                    'method' => 'getCacheKey',
                    'name' => $name,
                ];

                return 'CraftCacheKey';
            }
        );

        $mock->method('isFresh')->willReturnCallback(
            function (string $name, int $time): bool {
                $this->calls[] = [
                    'object' => 'CraftLoader',
                    'method' => 'isFresh',
                    'name' => $name,
                    'time' => $time,
                ];

                return true;
            }
        );

        $mock->method('exists')->willReturnCallback(
            function (string $name): bool {
                $this->calls[] = [
                    'object' => 'CraftLoader',
                    'method' => 'exists',
                    'name' => $name,
                ];

                return true;
            }
        );

        return $mock;
    }

    /**
     * @throws LoaderError
     */
    public function testGetSourceContextAnsel(): void
    {
        self::assertSame(
            $this->source,
            $this->loader->getSourceContext('@AnselSrc/Test'),
        );

        self::assertSame(
            [
                [
                    'object' => 'AnselLoader',
                    'method' => 'getSourceContext',
                    'name' => '@AnselSrc/Test',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws LoaderError
     */
    public function testGetSourceContextCraft(): void
    {
        self::assertSame(
            $this->source,
            $this->loader->getSourceContext('Test'),
        );

        self::assertSame(
            [
                [
                    'object' => 'CraftLoader',
                    'method' => 'getSourceContext',
                    'name' => 'Test',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws LoaderError
     */
    public function testGetCacheKeyAnsel(): void
    {
        self::assertSame(
            'AnselCacheKey',
            $this->loader->getCacheKey('@AnselSrc/Test'),
        );

        self::assertSame(
            [
                [
                    'object' => 'AnselLoader',
                    'method' => 'getCacheKey',
                    'name' => '@AnselSrc/Test',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws LoaderError
     */
    public function testGetCacheKeyCraft(): void
    {
        self::assertSame(
            'CraftCacheKey',
            $this->loader->getCacheKey('Test'),
        );

        self::assertSame(
            [
                [
                    'object' => 'CraftLoader',
                    'method' => 'getCacheKey',
                    'name' => 'Test',
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws LoaderError
     */
    public function testIsFreshAnsel(): void
    {
        self::assertTrue(
            $this->loader->isFresh(
                '@AnselSrc/Test',
                654,
            ),
        );

        self::assertSame(
            [
                [
                    'object' => 'AnselLoader',
                    'method' => 'isFresh',
                    'name' => '@AnselSrc/Test',
                    'time' => 654,
                ],
            ],
            $this->calls,
        );
    }

    /**
     * @throws LoaderError
     */
    public function testIsFreshCraft(): void
    {
        self::assertTrue(
            $this->loader->isFresh(
                'Test',
                876,
            ),
        );

        self::assertSame(
            [
                [
                    'object' => 'CraftLoader',
                    'method' => 'isFresh',
                    'name' => 'Test',
                    'time' => 876,
                ],
            ],
            $this->calls,
        );
    }

    public function testExistsAnsel(): void
    {
        self::assertTrue($this->loader->exists(
            '@AnselSrc/Test',
        ));

        self::assertSame(
            [
                [
                    'object' => 'AnselLoader',
                    'method' => 'exists',
                    'name' => '@AnselSrc/Test',
                ],
            ],
            $this->calls,
        );
    }

    public function testExistsCraft(): void
    {
        self::assertTrue($this->loader->exists('Test'));

        self::assertSame(
            [
                [
                    'object' => 'CraftLoader',
                    'method' => 'exists',
                    'name' => 'Test',
                ],
            ],
            $this->calls,
        );
    }
}
