<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Field\Settings\PopulateFieldSettingsFromDefaults;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

class GetSettingsHtml
{
    private GetFieldSettings $getFieldSettings;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults;

    public function __construct(
        GetFieldSettings $getFieldSettings,
        FieldSettingsFromRaw $fieldSettingsFromRaw,
        PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults
    ) {
        $this->fieldSettingsFromRaw              = $fieldSettingsFromRaw;
        $this->populateFieldSettingsFromDefaults = $populateFieldSettingsFromDefaults;
        $this->getFieldSettings                  = $getFieldSettings;
    }

    /**
     * @param array<(float|int|string|bool|null)> $rawEeFieldSettings
     *
     * @throws SyntaxError
     * @throws InvalidConfigException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function get(
        array $rawEeFieldSettings,
        bool $isNew
    ): string {
        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $rawEeFieldSettings,
        );

        if ($isNew) {
            $this->populateFieldSettingsFromDefaults->populate(
                $fieldSettings,
            );
        }

        return $this->getFieldSettings->render(
            $fieldSettings,
        )->content();
    }
}
