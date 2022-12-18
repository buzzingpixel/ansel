<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class FieldSettingsFromRaw
{
    /**
     * @param scalar[] $rawEeFieldSettings
     * @param scalar   $required
     */
    public function get(
        array $rawEeFieldSettings,
        $required = '0'
    ): FieldSettingsCollection {
        unset($rawEeFieldSettings['placeholder']);

        if ($required === '1') {
            $minQty = (int) ($rawEeFieldSettings['minQty'] ?? '');

            if ($minQty < 1) {
                $rawEeFieldSettings['minQty'] = '1';
            }
        }

        return FieldSettingsCollection::fromFieldArray(
            $rawEeFieldSettings,
        );
    }
}
