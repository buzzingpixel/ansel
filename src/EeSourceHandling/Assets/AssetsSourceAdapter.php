<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Assets;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

use function dd;

class AssetsSourceAdapter implements AnselSourceAdapter
{
    public static function createInstance(): ?self
    {
        return null;
    }

    public static function isEnabled(): bool
    {
        return true;
    }

    public static function getShortName(): string
    {
        return 'assets';
    }

    public static function getDisplayName(): string
    {
        return 'Assets';
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        // TODO: Implement getModalLink() method.
        dd('TODO: Implement getModalLink() method.');
    }
}
