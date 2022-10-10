<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

interface AnselSourceAdapter
{
    public static function createInstance(): ?self;

    public static function isEnabled(): bool;

    public static function getShortName(): string;

    public static function getDisplayName(): string;

    public function getModalLink(
        FieldSettingsCollection $fieldSettings
    ): string;
}
