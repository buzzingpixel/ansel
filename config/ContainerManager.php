<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use BuzzingPixel\AnselConfig\Bindings\AddonFactoryBinding;
use BuzzingPixel\AnselConfig\Bindings\AssetsBinding;
use BuzzingPixel\AnselConfig\Bindings\ClockBinding;
use BuzzingPixel\AnselConfig\Bindings\CraftDbConnection;
use BuzzingPixel\AnselConfig\Bindings\CraftWebView;
use BuzzingPixel\AnselConfig\Bindings\EEAlertCollectionBinding;
use BuzzingPixel\AnselConfig\Bindings\EECIDBForge;
use BuzzingPixel\AnselConfig\Bindings\EECP;
use BuzzingPixel\AnselConfig\Bindings\EECPURLFactory;
use BuzzingPixel\AnselConfig\Bindings\EECsrf;
use BuzzingPixel\AnselConfig\Bindings\EeCssJsBinding;
use BuzzingPixel\AnselConfig\Bindings\EEFunctionsBinding;
use BuzzingPixel\AnselConfig\Bindings\EELang;
use BuzzingPixel\AnselConfig\Bindings\EEModelFacade;
use BuzzingPixel\AnselConfig\Bindings\FieldSettingsConfig;
use BuzzingPixel\AnselConfig\Bindings\FilePickerConfig;
use BuzzingPixel\AnselConfig\Bindings\GetProcessingUrlBinding;
use BuzzingPixel\AnselConfig\Bindings\GetUploadKeyBinding;
use BuzzingPixel\AnselConfig\Bindings\GetUploadUrlBinding;
use BuzzingPixel\AnselConfig\Bindings\GuzzleConfig;
use BuzzingPixel\AnselConfig\Bindings\LocationGettersBinding;
use BuzzingPixel\AnselConfig\Bindings\Migrations;
use BuzzingPixel\AnselConfig\Bindings\SaveUploadKeyBinding;
use BuzzingPixel\AnselConfig\Bindings\ServerRequest;
use BuzzingPixel\AnselConfig\Bindings\SettingsRepository;
use BuzzingPixel\AnselConfig\Bindings\SiteMetaConfig;
use BuzzingPixel\AnselConfig\Bindings\TranslatorBinding;
use BuzzingPixel\AnselConfig\Bindings\TreasuryBindings;
use BuzzingPixel\AnselConfig\Bindings\Twig;
use BuzzingPixel\AnselConfig\Bindings\UuidBinding;
use BuzzingPixel\AnselConfig\Bindings\ValidateUploadKeyBinding;
use BuzzingPixel\AnselConfig\Bindings\VolumesBinding;
use BuzzingPixel\AnselConfig\ConstructorConfigs\FeedConfig;
use BuzzingPixel\AnselConfig\ConstructorConfigs\LicensePingConfig;
use BuzzingPixel\AnselConfig\ConstructorConfigs\PathsConfig;
use BuzzingPixel\Container\Container;
use Psr\Container\ContainerInterface;

use function array_merge;
use function define;
use function defined;
use function dirname;
use function file_get_contents;
use function json_decode;

class ContainerManager
{
    private static ?ContainerInterface $builtContainer = null;

    public function container(): ContainerInterface
    {
        if (self::$builtContainer !== null) {
            return self::$builtContainer;
        }

        $composerJson = json_decode(
            (string) file_get_contents(
                dirname(__DIR__) . '/composer.json',
            )
        );

        if (! defined('ANSEL_VER')) {
            /** @phpstan-ignore-next-line */
            define('ANSEL_VER', $composerJson->version);
        }

        $container = new Container(
            array_merge(
                AddonFactoryBinding::get(),
                AssetsBinding::get(),
                ClockBinding::get(),
                CraftDbConnection::get(),
                CraftWebView::get(),
                EEAlertCollectionBinding::get(),
                EECIDBForge::get(),
                EECP::get(),
                EECPURLFactory::get(),
                EECsrf::get(),
                EeCssJsBinding::get(),
                EEFunctionsBinding::get(),
                EELang::get(),
                EEModelFacade::get(),
                FieldSettingsConfig::get(),
                FilePickerConfig::get(),
                GetProcessingUrlBinding::get(),
                GetUploadKeyBinding::get(),
                GetUploadUrlBinding::get(),
                GuzzleConfig::get(),
                LocationGettersBinding::get(),
                Migrations::get(),
                SaveUploadKeyBinding::get(),
                ServerRequest::get(),
                SettingsRepository::get(),
                SiteMetaConfig::get(),
                TranslatorBinding::get(),
                TreasuryBindings::get(),
                Twig::get(),
                UuidBinding::get(),
                ValidateUploadKeyBinding::get(),
                VolumesBinding::get(),
            ),
            array_merge(
                FeedConfig::get(),
                LicensePingConfig::get(),
                PathsConfig::get(),
            ),
        );

        self::$builtContainer = $container;

        return $container;
    }
}
