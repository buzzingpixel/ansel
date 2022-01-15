<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\CraftResponseFactory;
use BuzzingPixel\Ansel\Shared\Facades\CraftUrlHelper;
use craft\web\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostIndexActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private Response $mockedResponse;

    private PostIndexAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->mockedResponse = $this->mockResponse();

        $this->action = new PostIndexAction(
            $this->mockUrlHelper(),
            $this->mockResponseFactory(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockUrlHelper(): CraftUrlHelper
    {
        $mock = $this->createMock(CraftUrlHelper::class);

        $mock->method('cpUrl')->willReturnCallback(
            function (string $path): string {
                $this->calls[] = [
                    'object' => 'CraftUrlHelper',
                    'method' => 'cpUrl',
                    'path' => $path,
                ];

                return '/foo/bar/cp/url';
            }
        );

        return $mock;
    }

    private function mockResponseFactory(): CraftResponseFactory
    {
        $mock = $this->createMock(
            CraftResponseFactory::class,
        );

        $mock->method('getResponse')->willReturn(
            $this->mockedResponse,
        );

        return $mock;
    }

    private function mockResponse(): Response
    {
        $mock = $this->createMock(Response::class);

        $mock->method('redirect')->willReturnCallback(
            function (string $url) use ($mock): Response {
                $this->calls[] = [
                    'object' => 'Response',
                    'method' => 'redirect',
                    'url' => $url,
                ];

                return $mock;
            }
        );

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturnCallback(
            static function (): SettingsCollection {
                return new SettingsCollection([
                    new SettingItem(
                        'string',
                        'foo-bar-key-1',
                        'foo-bar-label-1',
                        '',
                        'foo-bar-desc-1',
                        false,
                    ),
                    new SettingItem(
                        'string',
                        'foo-bar-key-2',
                        'foo-bar-label-2',
                        '',
                        'foo-bar-desc-2',
                        true,
                    ),
                    new SettingItem(
                        'int',
                        'foo-bar-key-3',
                        'foo-bar-label-3',
                        0,
                        'foo-bar-desc-3',
                        true,
                    ),
                    new SettingItem(
                        'bool',
                        'foo-bar-key-4',
                        'foo-bar-label-4',
                        false,
                        'foo-bar-desc-4',
                        true,
                    ),
                    new SettingItem(
                        'bool',
                        'foo-bar-key-5',
                        'foo-bar-label-5',
                        true,
                        'foo-bar-desc-5',
                        true,
                    ),
                ]);
            }
        );

        $mock->method('saveSettings')->willReturnCallback(
            function (SettingsCollection $settings): void {
                $this->calls[] = [
                    'object' => 'SettingsRepository',
                    'method' => 'saveSettings',
                    'settings' => $settings,
                ];
            }
        );

        return $mock;
    }

    public function testRun(): void
    {
        $request = $this->createMock(
            ServerRequestInterface::class,
        );

        $request->method('getParsedBody')->willReturn([
            'foo-bar-key-1' => 'foo-bar-value-1',
            'foo-bar-key-2' => 'foo-bar-value-2',
            'foo-bar-key-3' => 432,
            'foo-bar-key-4' => '1',
            'foo-bar-key-5' => '0',
        ]);

        $response = $this->action->run($request);

        self::assertSame($this->mockedResponse, $response);

        self::assertCount(3, $this->calls);

        $call0 = $this->calls[0];

        assert(is_array($call0));

        self::assertSame(
            'SettingsRepository',
            $call0['object'],
        );

        self::assertSame(
            'saveSettings',
            $call0['method'],
        );

        $settings = $call0['settings'];

        assert($settings instanceof SettingsCollection);

        self::assertSame(
            [
                [
                    'key' => 'foo-bar-key-2',
                    'value' => 'foo-bar-value-2',
                ],
                [
                    'key' => 'foo-bar-key-3',
                    'value' => 432,
                ],
                [
                    'key' => 'foo-bar-key-4',
                    'value' => true,
                ],
                [
                    'key' => 'foo-bar-key-5',
                    'value' => false,
                ],
            ],
            $settings->map(
                static fn (SettingItem $i) => [
                    'key' => $i->key(),
                    'value' => $i->value(),
                ],
            ),
        );

        self::assertSame(
            [
                'object' => 'CraftUrlHelper',
                'method' => 'cpUrl',
                'path' => 'ansel',
            ],
            $this->calls[1],
        );

        self::assertSame(
            [
                'object' => 'Response',
                'method' => 'redirect',
                'url' => '/foo/bar/cp/url',
            ],
            $this->calls[2],
        );
    }
}
