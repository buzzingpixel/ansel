<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Treasury;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

use function dd;

class TreasurySourceAdapter implements AnselSourceAdapter
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
        return 'treasury';
    }

    public static function getDisplayName(): string
    {
        return 'Treasury';
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        // TODO: Implement getModalLink() method.
        dd('TODO: Implement getModalLink() method.');
    }
}
