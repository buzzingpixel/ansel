<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;

class PopulateFieldSettingsFromDefaults
{
    private SettingsRepositoryContract $settingsRepository;

    public function __construct(SettingsRepositoryContract $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function populate(FieldSettingsCollection $fieldSettings): void
    {
        $settings = $this->settingsRepository->getSettings();

        $maxQty = $settings->getByKey('default_max_qty');
        $fieldSettings->maxQty()->setValue($maxQty->getInt());

        $imgQuality = $settings->getByKey('default_image_quality');
        $fieldSettings->quality()->setValue($imgQuality->getInt());

        $retinaMode = $settings->getByKey('default_jpg');
        $fieldSettings->forceJpg()->setValue($retinaMode->getBool());

        $retinaMode = $settings->getByKey('default_retina');
        $fieldSettings->retinaMode()->setValue($retinaMode->getBool());
    }
}
