<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;

class ResetLicenseValidity
{
    private SettingsRepositoryContract $settingsRepository;

    public function __construct(SettingsRepositoryContract $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function reset(): void
    {
        $allSettings = $this->settingsRepository->getSettings();

        $encodingData = $allSettings->getByKey('encoding_data');
        $encodingData->setValue('');
        $this->settingsRepository->saveSetting($encodingData);

        $phoneHome = $allSettings->getByKey('phone_home');
        $phoneHome->setValue(0);
        $this->settingsRepository->saveSetting($phoneHome);
    }
}
