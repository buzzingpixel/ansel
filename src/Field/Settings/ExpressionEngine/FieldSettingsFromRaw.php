<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class FieldSettingsFromRaw
{
    /**
     * @param mixed[] $rawEeFieldSettings
     */
    public function get(
        array $rawEeFieldSettings,
        bool $useRequired = false
    ): FieldSettingsCollection {
        if ($useRequired) {
            $fieldRequiredString = $rawEeFieldSettings['field_required'] ?? 'n';

            $fieldRequired = $fieldRequiredString === 'y';

            $minQty = (int) (
                $rawEeFieldSettings['ansel']['fieldSettings']['minQty'] ?? ''
            );

            if ($fieldRequired && $minQty < 1) {
                $rawEeFieldSettings['ansel']['fieldSettings']['minQty'] = '1';
            }
        }

        /** @phpstan-ignore-next-line */
        $fieldSettingsArray = $rawEeFieldSettings['ansel']['fieldSettings'] ?? [];

        /** @phpstan-ignore-next-line */
        unset($fieldSettingsArray['placeholder']);

        return FieldSettingsCollection::fromFieldArray(
        /** @phpstan-ignore-next-line */
            $fieldSettingsArray,
        );
    }
}
