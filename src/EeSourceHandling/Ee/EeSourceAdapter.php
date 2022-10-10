<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;

class EeSourceAdapter implements AnselSourceAdapter
{
    private EeStorageLocations $eeStorageLocations;

    private EeModalLink $eeModalLink;

    public function __construct(
        EeStorageLocations $eeStorageLocations,
        EeModalLink $eeModalLink
    ) {
        $this->eeStorageLocations = $eeStorageLocations;
        $this->eeModalLink        = $eeModalLink;
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

    public function getAllStorageLocations(): StorageLocationCollection
    {
        return $this->eeStorageLocations->getAll();
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        return $this->eeModalLink->getLink($fieldSettings);
    }
}
