<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use BuzzingPixel\Ansel\Field\Settings\Craft\GetAllVolumes;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

use function array_filter;
use function array_keys;

class FieldSettingsCollectionValidatorCraft
{
    private GetAllVolumes $getAllVolumes;

    private TranslatorContract $translator;

    public function __construct(
        GetAllVolumes $getAllVolumes,
        TranslatorContract $translator
    ) {
        $this->getAllVolumes = $getAllVolumes;
        $this->translator    = $translator;
    }

    /**
     * @return string[]
     */
    public function validate(
        FieldSettingsCollection $collection
    ): array {
        $volumes = $this->getAllVolumes->get();

        $errors = array_filter(
            $collection->map(
                static function (FieldSettingItemContract $item) {
                    return $item->required() && $item->isEmpty();
                }
            ),
            /** @phpstan-ignore-next-line */
            static fn (bool $hasError) => $hasError,
        );

        $uploadVolume = $volumes->getLocationByValueOrNull(
            $collection->uploadLocation()->value(),
        );

        if ($uploadVolume === null) {
            $errors['uploadLocation'] = true;
        }

        $saveVolume = $volumes->getLocationByValueOrNull(
            $collection->saveLocation()->value(),
        );

        if ($saveVolume === null) {
            $errors['saveLocation'] = true;
        }

        $finalErrors = [];

        foreach (array_keys($errors) as $prop) {
            $finalErrors[$prop] = $this->translator->getLine(
                'setting_required',
            );
        }

        return $finalErrors;
    }
}
