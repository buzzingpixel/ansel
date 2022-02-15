<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

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
use BuzzingPixel\AnselConfig\Bindings\GuzzleConfig;
use BuzzingPixel\AnselConfig\Bindings\Migrations;
use BuzzingPixel\AnselConfig\Bindings\ServerRequest;
use BuzzingPixel\AnselConfig\Bindings\SettingsRepository;
use BuzzingPixel\AnselConfig\Bindings\TranslatorBinding;
use BuzzingPixel\AnselConfig\Bindings\Twig;
use BuzzingPixel\AnselConfig\ConstructorConfigs\FeedConfig;
use BuzzingPixel\AnselConfig\ConstructorConfigs\LicensePingConfig;
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
                GuzzleConfig::get(),
                Migrations::get(),
                ServerRequest::get(),
                SettingsRepository::get(),
                TranslatorBinding::get(),
                Twig::get(),
            ),
            array_merge(
                FeedConfig::get(),
                LicensePingConfig::get(),
            ),
        );

        self::$builtContainer = $container;

        return $container;
    }
}
