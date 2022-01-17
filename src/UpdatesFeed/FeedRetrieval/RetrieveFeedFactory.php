<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;

class RetrieveFeedFactory
{
    private RetrieveFeedFresh $retrieveFeedFresh;

    private InternalFunctions $internalFunctions;

    private RetrieveFeedFromCache $retrieveFeedFromCache;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        RetrieveFeedFresh $retrieveFeedFresh,
        InternalFunctions $internalFunctions,
        RetrieveFeedFromCache $retrieveFeedFromCache,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->retrieveFeedFresh     = $retrieveFeedFresh;
        $this->internalFunctions     = $internalFunctions;
        $this->retrieveFeedFromCache = $retrieveFeedFromCache;
        $this->settingsRepository    = $settingsRepository;
    }

    public function get(): RetrieveFeedContract
    {
        $allSettings = $this->settingsRepository->getSettings();

        $checkForUpdates = $allSettings
            ->getByKey('check_for_updates')
            ->getInt();

        // If it's time to check the feed
        if ($checkForUpdates < $this->internalFunctions->time()) {
            return $this->retrieveFeedFresh;
        }

        $cache = $allSettings->getByKey('update_feed')->getString();

        // If there's no feed in the cache
        if ($cache === '') {
            return $this->retrieveFeedFresh;
        }

        return $this->retrieveFeedFromCache;
    }
}
