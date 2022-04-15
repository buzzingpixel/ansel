<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

class DimensionsNotMetTranslationFactory
{
    private TranslatorContract $translator;

    public function __construct(TranslatorContract $translator)
    {
        $this->translator = $translator;
    }

    public function get(FieldSettingsCollection $fieldSettings): string
    {
        $minWidth = $fieldSettings->minWidth()->value();

        $minHeight = $fieldSettings->minHeight()->value();

        if ($minWidth > 0 && $minHeight > 0) {
            return $this->translator->getLineWithReplacements(
                'min_image_dimensions_not_met_width_and_height',
                [
                    '{{minWidth}}' => (string) $minWidth,
                    '{{minHeight}}' => (string) $minHeight,
                ],
            );
        }

        if ($minWidth > 0) {
            return $this->translator->getLineWithReplacements(
                'min_image_dimensions_not_met_width_only',
                ['{{minWidth}}' => (string) $minWidth],
            );
        }

        if ($minHeight > 0) {
            return $this->translator->getLineWithReplacements(
                'min_image_dimensions_not_met_height_only',
                ['{{minHeight}}' => (string) $minHeight],
            );
        }

        return '';
    }
}
