<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\EE;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Cp;
use PHPUnit\Framework\TestCase;

use function json_encode;

class EeCssJsTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private EeCssJs $eeCssJs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eeCssJs = new EeCssJs(
            $this->mockCp(),
            '/foo/bar/path/third/themes',
            '/foo/bar/url/third/themes',
            $this->mockInternalFunctions(),
        );
    }

    private function mockCp(): Cp
    {
        $mock = $this->createMock(Cp::class);

        $mock->method('add_to_head')->willReturnCallback(
            function (string $data): void {
                $this->calls[] = [
                    'object' => 'Cp',
                    'method' => 'add_to_head',
                    'data' => $data,
                ];
            }
        );

        $mock->method('add_to_foot')->willReturnCallback(
            function (string $data): void {
                $this->calls[] = [
                    'object' => 'Cp',
                    'method' => 'add_to_foot',
                    'data' => $data,
                ];
            }
        );

        return $mock;
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
                    'ansel.min.js' => '/foo/bar/ansel.min.js',
                    'ansel.min.css' => '/foo/bar/ansel.min.css',
                    'test.1' => 'foo/bar/test.1',
                    'test.2' => 'foo/bar/test.2',
                ]);
            }
        );

        return $mock;
    }

    public function testAdd(): void
    {
        $this->eeCssJs->add();

        self::assertSame(
            [
                [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'fileName' => '/foo/bar/path/third/themes/ansel/css/manifest.json',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_head',
                    'data' => '<link rel="stylesheet" href="/foo/bar/url/third/themes/ansel/css//foo/bar/ansel.min.js">',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_head',
                    'data' => '<link rel="stylesheet" href="/foo/bar/url/third/themes/ansel/css/foo/bar/test.1">',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_head',
                    'data' => '<link rel="stylesheet" href="/foo/bar/url/third/themes/ansel/css/foo/bar/test.2">',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_head',
                    'data' => '<link rel="stylesheet" href="/foo/bar/url/third/themes/ansel/css//foo/bar/ansel.min.css">',
                ],
                [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'fileName' => '/foo/bar/path/third/themes/ansel/js/manifest.json',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_foot',
                    'data' => '<script type="text/javascript" src="/foo/bar/url/third/themes/ansel/js//foo/bar/ansel.min.css"></script>',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_foot',
                    'data' => '<script type="text/javascript" src="/foo/bar/url/third/themes/ansel/js/foo/bar/test.1"></script>',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_foot',
                    'data' => '<script type="text/javascript" src="/foo/bar/url/third/themes/ansel/js/foo/bar/test.2"></script>',
                ],
                [
                    'object' => 'Cp',
                    'method' => 'add_to_foot',
                    'data' => '<script type="text/javascript" src="/foo/bar/url/third/themes/ansel/js//foo/bar/ansel.min.js"></script>',
                ],
            ],
            $this->calls,
        );
    }
}
