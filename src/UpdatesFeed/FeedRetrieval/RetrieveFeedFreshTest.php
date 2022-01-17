<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use function assert;
use function file_get_contents;
use function is_array;

class RetrieveFeedFreshTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private RetrieveFeedFresh $retrieveFeed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->retrieveFeed = new RetrieveFeedFresh(
            '/foo/feed/url',
            $this->mockGuzzleClient(),
            $this->mockInternalFunctions(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockGuzzleClient(): GuzzleClient
    {
        $mock = $this->createMock(GuzzleClient::class);

        $mock->method('get')->willReturnCallback(
            function (string $uri): ResponseInterface {
                $this->calls[] = [
                    'object' => 'GuzzleClient',
                    'method' => 'get',
                    'uri' => $uri,
                ];

                return $this->mockGuzzleResponse();
            }
        );

        return $mock;
    }

    private function mockGuzzleResponse(): ResponseInterface
    {
        $mock = $this->createMock(ResponseInterface::class);

        $mock->method('getBody')->willReturn(
            $this->mockGuzzleStream(),
        );

        return $mock;
    }

    private function mockGuzzleStream(): StreamInterface
    {
        $mock = $this->createMock(StreamInterface::class);

        $mock->method('getContents')->willReturn(
            file_get_contents(
                __DIR__ . '/UpdatesFeedForTesting.json',
            ),
        );

        return $mock;
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('time')->willReturn(123);

        $mock->method('strToTime')->willReturnCallback(
            function (string $dateTime, int $baseTimestamp): int {
                $this->calls[] = [
                    'object' => 'InternalFunctions',
                    'method' => 'strToTime',
                    'dateTime' => $dateTime,
                    'baseTimestamp' => $baseTimestamp,
                ];

                return 456;
            }
        );

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturn(
            new SettingsCollection([
                new SettingItem(
                    'string',
                    'update_feed',
                    'update_feed',
                    'foo-bar-update-feed-value',
                    'update_feed',
                    false,
                ),
                new SettingItem(
                    'string',
                    'check_for_updates',
                    'check_for_updates',
                    789,
                    'check_for_updates',
                    false,
                ),
            ]),
        );

        $mock->method('saveSetting')->willReturnCallback(
            function (SettingItem $setting): void {
                $this->calls[] = [
                    'object' => 'SettingsRepositoryContract',
                    'method' => 'saveSetting',
                    'setting' => $setting,
                ];
            }
        );

        return $mock;
    }

    public function testRetrieve(): void
    {
        $feed = $this->retrieveFeed->retrieve();

        self::assertSame(
            '[{"version":"2.2.2"},{"version":"2.2.1"},{"version":"2.2.0"},{"version":"2.1.5"},{"version":"2.1.4"},{"version":"2.1.3"}]',
            $feed,
        );

        self::assertCount(4, $this->calls);

        self::assertSame(
            [
                'object' => 'GuzzleClient',
                'method' => 'get',
                'uri' => '/foo/feed/url',
            ],
            $this->calls[0],
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        self::assertSame(
            'SettingsRepositoryContract',
            $call1['object'],
        );

        self::assertSame(
            'saveSetting',
            $call1['method'],
        );

        $saveUpdateFeedSetting = $call1['setting'];

        assert($saveUpdateFeedSetting instanceof SettingItem);

        self::assertSame(
            [
                'key' => 'update_feed',
                'value' => $feed,
            ],
            [
                'key' => $saveUpdateFeedSetting->key(),
                'value' => $saveUpdateFeedSetting->value(),
            ],
        );

        self::assertSame(
            [
                'object' => 'InternalFunctions',
                'method' => 'strToTime',
                'dateTime' => '+1 day',
                'baseTimestamp' => 123,
            ],
            $this->calls[2],
        );

        $call3 = $this->calls[3];

        assert(is_array($call3));

        self::assertSame(
            'SettingsRepositoryContract',
            $call3['object'],
        );

        self::assertSame(
            'saveSetting',
            $call3['method'],
        );

        $saveCheckForUpdateSetting = $call3['setting'];

        assert($saveCheckForUpdateSetting instanceof SettingItem);

        self::assertSame(
            [
                'key' => 'check_for_updates',
                'value' => 456,
            ],
            [
                'key' => $saveCheckForUpdateSetting->key(),
                'value' => $saveCheckForUpdateSetting->value(),
            ],
        );
    }
}
