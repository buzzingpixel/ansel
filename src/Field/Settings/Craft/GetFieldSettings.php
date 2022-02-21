<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsRenderModel;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\Craft\AssetBundles\CraftRegisterAssetBundle;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

class GetFieldSettings
{
    private TwigEnvironment $twig;

    private GetAllVolumes $getAllVolumes;

    private TranslatorContract $translator;

    private SettingsRepositoryContract $settingsRepository;

    private CraftRegisterAssetBundle $registerAssetBundle;

    private RegisterFieldSettingsAssetBundle $registerFieldSettingsAssetBundle;

    public function __construct(
        TwigEnvironment $twig,
        GetAllVolumes $getAllVolumes,
        TranslatorContract $translator,
        SettingsRepositoryContract $settingsRepository,
        CraftRegisterAssetBundle $registerAssetBundle,
        RegisterFieldSettingsAssetBundle $registerFieldSettingsAssetBundle
    ) {
        $this->twig                             = $twig;
        $this->getAllVolumes                    = $getAllVolumes;
        $this->translator                       = $translator;
        $this->settingsRepository               = $settingsRepository;
        $this->registerAssetBundle              = $registerAssetBundle;
        $this->registerFieldSettingsAssetBundle = $registerFieldSettingsAssetBundle;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws InvalidConfigException
     */
    public function render(
        FieldSettingsCollection $fieldSettings
    ): GetFieldSettingsModel {
        $this->registerAssetBundle->register();

        $this->registerFieldSettingsAssetBundle->register();

        return new GetFieldSettingsModel(
            $this->twig->render(
                '@AnselSrc/Field/Settings/Craft/FieldSettings.twig',
                [
                    'model' => new FieldSettingsRenderModel(
                        $fieldSettings,
                        $this->settingsRepository
                            ->getSettings()
                            ->getByKey('hide_source_save_instructions')
                            ->getBool(),
                        $this->translator->getLine(
                            'upload_save_dir_explanation'
                        ),
                        $this->translator->getLine(
                            'upload_save_dir_hide'
                        ),
                        $this->translator->getLine(
                            'upload_save_dir_explain_upload'
                        ),
                        $this->translator->getLine(
                            'upload_save_dir_explain_save'
                        ),
                        $this->translator->getLine(
                            'upload_save_dir_explain_different_sources'
                        ),
                        $this->getAllVolumes->get(),
                    ),
                    'translator' => $this->translator,
                ],
            )
        );
    }
}
