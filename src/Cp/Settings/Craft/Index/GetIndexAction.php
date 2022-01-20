<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\CraftRegisterAssetBundle;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

class GetIndexAction
{
    private TwigEnvironment $twig;

    private TranslatorContract $translator;

    private CraftRegisterAssetBundle $registerAssetBundle;

    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        TwigEnvironment $twig,
        TranslatorContract $translator,
        CraftRegisterAssetBundle $registerAssetBundle,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->twig                = $twig;
        $this->registerAssetBundle = $registerAssetBundle;
        $this->settingsRepository  = $settingsRepository;
        $this->translator          = $translator;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     */
    public function render(): GetIndexModel
    {
        $this->registerAssetBundle->register();

        return new GetIndexModel(
            'Ansel ' . $this->translator->getLine('settings'),
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Craft/Index/Index.twig',
                [
                    'settingsCollection' => $this->settingsRepository
                        ->getSettings()
                        ->settingsPageOnly(),
                ]
            ),
        );
    }
}
