<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\License;

use BuzzingPixel\Ansel\License\LicensePing;
use BuzzingPixel\Ansel\License\ResetLicenseValidity;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use EE_Functions;
use ExpressionEngine\Service\URL\URLFactory;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ServerRequestInterface;

class PostLicenseAction
{
    private URLFactory $urlFactory;

    private LicensePing $licensePing;

    private EE_Functions $eeFunctions;

    private ResetLicenseValidity $resetLicenseValidity;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        URLFactory $urlFactory,
        LicensePing $licensePing,
        EE_Functions $eeFunctions,
        ResetLicenseValidity $resetLicenseValidity,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->urlFactory           = $urlFactory;
        $this->licensePing          = $licensePing;
        $this->eeFunctions          = $eeFunctions;
        $this->resetLicenseValidity = $resetLicenseValidity;
        $this->settingsRepository   = $settingsRepository;
    }

    /**
     * @throws GuzzleException
     */
    public function run(ServerRequestInterface $request): void
    {
        // Get posted license key
        $postValues       = (array) $request->getParsedBody();
        $postedLicenseKey = (string) ($postValues['license_key'] ?? '');

        // Get settings
        $allSettings = $this->settingsRepository->getSettings();

        // Set the value of the posted license key
        $licenseKeySetting = $allSettings->getByKey('license_key');
        $licenseKeySetting->setValue($postedLicenseKey);
        $this->settingsRepository->saveSetting($licenseKeySetting);

        // Reset license validity
        $this->resetLicenseValidity->reset();

        // Run license ping
        $this->licensePing->runFromWebRequest();

        // Redirect to a get request
        $this->eeFunctions->redirect(
            $this->urlFactory->getCurrentUrl(),
        );
    }
}
