<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class EeSourceAdapter implements AnselSourceAdapter
{
    private EeModalLink $eeModalLink;

    public function __construct(
        EeModalLink $eeModalLink
    ) {
        $this->eeModalLink = $eeModalLink;
    }

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
        return 'ee';
    }

    public static function getDisplayName(): string
    {
        return 'EE';
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        return $this->eeModalLink->getLink($fieldSettings);
    }
}
