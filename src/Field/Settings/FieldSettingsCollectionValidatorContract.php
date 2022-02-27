<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

interface FieldSettingsCollectionValidatorContract
{
    /**
     * @return string[]
     */
    public function validate(FieldSettingsCollection $collection): array;
}
