<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;

class LicenseStatus
{
    private InternalFunctions $internalFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        InternalFunctions $internalFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->internalFunctions  = $internalFunctions;
        $this->settingsRepository = $settingsRepository;
    }

    public function get(): LicenseResult
    {
        $allSettings = $this->settingsRepository->getSettings();

        $licenseKey = $allSettings->getByKey('license_key')->getString();

        $encoding = (int) $allSettings->getByKey('encoding')
            ->getFromBase64();

        $encodingData = $allSettings->getByKey('encoding_data')
            ->getFromBase64();

        $isNotExpired = $this->internalFunctions->time() < $encoding;

        if ($licenseKey !== '') {
            return $this->checkLicenseKey(
                $isNotExpired,
                $encodingData,
            );
        }

        if ($isNotExpired) {
            return new LicenseResult(LicenseResult::STATUS_TRIAL);
        }

        return new LicenseResult(LicenseResult::STATUS_EXPIRED);
    }

    private function checkLicenseKey(
        bool $isNotExpired,
        string $encodingData
    ): LicenseResult {
        if ($encodingData === 'invalid' && ! $isNotExpired) {
            return new LicenseResult(
                LicenseResult::STATUS_INVALID_EXPIRED,
            );
        }

        if ($encodingData === 'invalid' && $isNotExpired) {
            return new LicenseResult(
                LicenseResult::STATUS_INVALID_TRIAL,
            );
        }

        return new LicenseResult(LicenseResult::STATUS_VALID);
    }
}
