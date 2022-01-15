<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AssetsDist\AssetsDist;
use craft\web\assets\cp\CpAsset;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

use function assert;
use function json_encode;

class CraftAnselAssetBundleTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private CraftAnselAssetBundle $assetBundle;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $ref = new ReflectionClass(
            CraftAnselAssetBundle::class,
        );

        $assetBundle = $ref->newInstanceWithoutConstructor();

        /** @phpstan-ignore-next-line */
        assert($assetBundle instanceof CraftAnselAssetBundle);

        $property = $ref->getProperty('internalFunctions');

        $property->setAccessible(true);

        $property->setValue(
            $assetBundle,
            $this->mockInternalFunctions()
        );

        $this->assetBundle = $assetBundle;
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('fileGetContents')->willReturnCallback(
            function (string $fileName): string {
                $this->calls[] = [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'fileName' => $fileName,
                ];

                return (string) json_encode([
                    'main.js' => 'foo/bar/main.js',
                    'style.min.css' => 'foo/bar/style.min.css',
                    'test.1' => 'foo/bar/test.1',
                    'test.2' => 'foo/bar/test.2',
                ]);
            }
        );

        return $mock;
    }

    public function testRegisterBundle(): void
    {
        $this->assetBundle->registerBundle();

        self::assertSame(
            AssetsDist::getPath(),
            $this->assetBundle->sourcePath,
        );

        self::assertSame(
            [CpAsset::class],
            $this->assetBundle->depends,
        );

        self::assertSame(
            [
                'js/foo/bar/style.min.css',
                'js/foo/bar/test.1',
                'js/foo/bar/test.2',
                'js/foo/bar/main.js',
            ],
            $this->assetBundle->js,
        );

        self::assertSame(
            [
                'css/foo/bar/main.js',
                'css/foo/bar/test.1',
                'css/foo/bar/test.2',
                'css/foo/bar/style.min.css',
            ],
            $this->assetBundle->css,
        );

        self::assertSame(
            [
                [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'fileName' => $this->assetBundle->sourcePath . '/css/manifest.json',
                ],
                [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'fileName' => $this->assetBundle->sourcePath . '/js/manifest.json',
                ],
            ],
            $this->calls,
        );
    }
}
