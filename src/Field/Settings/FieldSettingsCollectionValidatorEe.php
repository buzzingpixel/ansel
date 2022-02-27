<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\GetAllLocations;
use BuzzingPixel\Ansel\Translations\TranslatorContract;

use function array_filter;
use function array_keys;

class FieldSettingsCollectionValidatorEe implements FieldSettingsCollectionValidatorContract
{
    private TranslatorContract $translator;
    private GetAllLocations $getAllLocations;

    public function __construct(
        TranslatorContract $translator,
        GetAllLocations $getAllLocations
    ) {
        $this->translator      = $translator;
        $this->getAllLocations = $getAllLocations;
    }

    /**
     * @inheritDoc
     */
    public function validate(FieldSettingsCollection $collection): array
    {
        $locations = $this->getAllLocations->get();

        $errors = array_filter(
            $collection->map(
                static function (FieldSettingItemContract $item) {
                    return $item->required() && $item->isEmpty();
                }
            ),
            /** @phpstan-ignore-next-line */
            static fn (bool $hasError) => $hasError,
        );

        $uploadLocation = $locations->getLocationByValueOrNull(
            $collection->uploadLocation()->value(),
        );

        if ($uploadLocation === null) {
            $errors['uploadLocation'] = true;
        }

        $saveLocation = $locations->getLocationByValueOrNull(
            $collection->saveLocation()->value(),
        );

        if ($saveLocation === null) {
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
