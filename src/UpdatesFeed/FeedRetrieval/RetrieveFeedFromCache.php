<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;

class RetrieveFeedFromCache implements RetrieveFeedContract
{
    private SettingsRepositoryContract $settingsRepository;

    public function __construct(SettingsRepositoryContract $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function retrieve(): string
    {
        return $this->settingsRepository->getSettings()
            ->getByKey('update_feed')
            ->getString();
    }
}
