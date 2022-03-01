<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\Craft\GetFieldSettingsModel;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsRenderModel;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetFieldSettings
{
    private EeCssJs $eeCssJs;
    private TwigEnvironment $twig;
    private TranslatorContract $translator;
    private GetAllLocations $getAllLocations;
    private SettingsRepositoryContract $settingsRepository;

    public function __construct(
        EeCssJs $eeCssJs,
        TwigEnvironment $twig,
        TranslatorContract $translator,
        GetAllLocations $getAllLocations,
        SettingsRepositoryContract $settingsRepository
    ) {
        $this->eeCssJs            = $eeCssJs;
        $this->twig               = $twig;
        $this->translator         = $translator;
        $this->getAllLocations    = $getAllLocations;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(
        string $fieldNameRoot,
        FieldSettingsCollection $fieldSettings
    ): GetFieldSettingsModel {
        $this->eeCssJs->add();

        $locationsCollection = $this->getAllLocations->get();

        return new GetFieldSettingsModel(
            $this->twig->render(
                '@AnselSrc/Field/Settings/ExpressionEngine/FieldSettings.twig',
                [
                    'fieldNameRoot' => $fieldNameRoot,
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
                        $locationsCollection,
                    ),
                    'translator' => $this->translator,
                ],
            ),
        );
    }
}
