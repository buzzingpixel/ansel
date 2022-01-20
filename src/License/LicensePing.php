<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ServerRequestInterface;

use function json_decode;

class LicensePing
{
    private string $appKey;

    private GuzzleClient $guzzleClient;

    private ServerRequestInterface $request;

    private InternalFunctions $internalFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        string $appKey,
        GuzzleClient $guzzleClient,
        ServerRequestInterface $request,
        InternalFunctions $internalFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->appKey             = $appKey;
        $this->guzzleClient       = $guzzleClient;
        $this->request            = $request;
        $this->internalFunctions  = $internalFunctions;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * This method should only ever be run from a web request because
     * we need to send the URL for validity checking
     *
     * @throws GuzzleException
     */
    public function runFromWebRequest(): void
    {
        $allSettings = $this->settingsRepository->getSettings();

        $licenseKey = $allSettings->getByKey('license_key')->getString();

        if ($licenseKey === '') {
            return;
        }

        $phoneHome = $allSettings->getByKey('phone_home');

        $encodingData = $allSettings->getByKey('encoding_data');

        if (
            $this->internalFunctions->time() < $phoneHome->getInt() &&
            $encodingData->getFromBase64() !== 'invalid'
        ) {
            return;
        }

        $response = $this->guzzleClient->post(
            'https://www.buzzingpixel.com/api/v1/check-license',
            [
                'form_params' => [
                    'app' => $this->appKey,
                    'domain' => $this->getServerName(),
                    'license' => $licenseKey,
                ],
            ]
        );

        $content = (array) json_decode(
            $response->getBody()->getContents(),
            true,
        );

        $encodingData->setToBase64(
            /** @phpstan-ignore-next-line */
            (string) ($content['message'] ?? 'invalid'),
        );

        $this->settingsRepository->saveSetting($encodingData);

        $phoneHome->setValue($this->internalFunctions->strToTime(
            '+1 day',
            $this->internalFunctions->time(),
        ));

        $this->settingsRepository->saveSetting($phoneHome);
    }

    private function getServerName(): string
    {
        $server = $this->request->getServerParams();

        if (isset($server['SERVER_NAME'])) {
            return (string) $server['SERVER_NAME'];
        }

        if (isset($server['HTTP_HOST'])) {
            return (string) $server['HTTP_HOST'];
        }

        return '';
    }
}
