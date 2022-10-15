<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use SplFileInfo;

interface AnselSourceAdapter
{
    public static function createInstance(): ?self;

    public static function isEnabled(): bool;

    public static function getShortName(): string;

    public static function getDisplayName(): string;

    public function getAllStorageLocations(): StorageLocationCollection;

    public function getModalLink(
        FieldSettingsCollection $fieldSettings
    ): string;

    public function getFileByIdentifier(string $identifier): ?File;

    /**
     * Adds a file to the implementer's file manager and records a record of it
     */
    public function addFile(
        string $locationIdentifier,
        SplFileInfo $file,
        string $memberId = ''
    ): File;

    /**
     * Uploads a file to the implementer's specified path but does not record a
     * record of it
     */
    public function uploadFile(
        string $locationIdentifier,
        SplFileInfo $file,
        ?string $subFolder = null
    ): SplFileInfo;
}
