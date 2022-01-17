<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use PHPUnit\Framework\TestCase;

class RetrieveFeedFactoryTest extends TestCase
{
    private int $checkForUpdatesValue = 0;

    private string $updateFeedValue = '';

    private RetrieveFeedFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkForUpdatesValue = 0;

        $this->updateFeedValue = '';

        $this->factory = new RetrieveFeedFactory(
            $this->createMock(
                RetrieveFeedFresh::class,
            ),
            $this->mockInternalFunctions(),
            $this->createMock(
                RetrieveFeedFromCache::class,
            ),
            $this->mockSettingsRepository(),
        );
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('time')->willReturn(555);

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturnCallback(
            function (): SettingsCollection {
                return new SettingsCollection([
                    new SettingItem(
                        'int',
                        'check_for_updates',
                        'check_for_updates',
                        $this->checkForUpdatesValue,
                        'check_for_updates',
                        false,
                    ),
                    new SettingItem(
                        'string',
                        'update_feed',
                        'update_feed',
                        $this->updateFeedValue,
                        'update_feed',
                        false,
                    ),
                ]);
            }
        );

        return $mock;
    }

    public function testGetWhenItsTimeToCheckForUpdates(): void
    {
        self::assertInstanceOf(
            RetrieveFeedFresh::class,
            $this->factory->get(),
        );
    }

    public function testWhenNoCache(): void
    {
        $this->checkForUpdatesValue = 556;

        self::assertInstanceOf(
            RetrieveFeedFresh::class,
            $this->factory->get(),
        );
    }

    public function testWhenCache(): void
    {
        $this->checkForUpdatesValue = 556;

        $this->updateFeedValue = 'foo-bar-value';

        self::assertInstanceOf(
            RetrieveFeedFromCache::class,
            $this->factory->get(),
        );
    }
}
