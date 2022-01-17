<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

use function array_slice;
use function json_decode;
use function json_encode;

class RetrieveFeedFresh implements RetrieveFeedContract
{
    private string $feedUrl;

    private GuzzleClient $guzzleClient;

    private InternalFunctions $internalFunctions;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        string $feedUrl,
        GuzzleClient $guzzleClient,
        InternalFunctions $internalFunctions,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->feedUrl            = $feedUrl;
        $this->guzzleClient       = $guzzleClient;
        $this->internalFunctions  = $internalFunctions;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @throws GuzzleException
     */
    public function retrieve(): string
    {
        $response = $this->guzzleClient->get(
            $this->feedUrl
        );

        $content = $response->getBody()->getContents();

        // Decode the json so we can limit the number of items to something
        // reasonable
        $json = (array) json_decode($content, true);

        $limited = array_slice(
            $json,
            0,
            6,
        );

        $content = (string) json_encode($limited);

        $allSettings = $this->settingsRepository->getSettings();

        $cacheItem = $allSettings->getByKey('update_feed');

        $cacheItem->setValue($content);

        $this->settingsRepository->saveSetting($cacheItem);

        $checkForUpdates = $allSettings->getByKey('check_for_updates');

        $checkForUpdates->setValue($this->internalFunctions->strToTime(
            '+1 day',
            $this->internalFunctions->time(),
        ));

        $this->settingsRepository->saveSetting($checkForUpdates);

        return $content;
    }
}
