<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use PHPUnit\Framework\TestCase;

class RetrieveFeedFromCacheTest extends TestCase
{
    private RetrieveFeedFromCache $retrieveFeedFromCache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->retrieveFeedFromCache = new RetrieveFeedFromCache(
            $this->mockSettingsRepository(),
        );
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
            ]),
        );

        return $mock;
    }

    public function testRetrieve(): void
    {
        self::assertSame(
            'foo-bar-update-feed-value',
            $this->retrieveFeedFromCache->retrieve(),
        );
    }
}
