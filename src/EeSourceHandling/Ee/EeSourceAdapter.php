<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Ee;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\EeSourceHandling\File;
use BuzzingPixel\Ansel\EeSourceHandling\FileCollection;
use BuzzingPixel\Ansel\EeSourceHandling\FileInstanceCollection;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use Exception;
use SplFileInfo;

class EeSourceAdapter implements AnselSourceAdapter
{
    private EeStorageLocations $eeStorageLocations;

    private EeModalLink $eeModalLink;

    private EeGetFileByIdentifier $getFileByIdentifier;

    private EeAddFile $addFile;

    private EeUploadFile $uploadFile;

    private EeGetFilesByIdentifiers $getFilesByIdentifiers;

    public function __construct(
        EeStorageLocations $eeStorageLocations,
        EeModalLink $eeModalLink,
        EeGetFileByIdentifier $getFileByIdentifier,
        EeAddFile $addFile,
        EeUploadFile $uploadFile,
        EeGetFilesByIdentifiers $getFilesByIdentifiers
    ) {
        $this->eeStorageLocations    = $eeStorageLocations;
        $this->eeModalLink           = $eeModalLink;
        $this->getFileByIdentifier   = $getFileByIdentifier;
        $this->addFile               = $addFile;
        $this->uploadFile            = $uploadFile;
        $this->getFilesByIdentifiers = $getFilesByIdentifiers;
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

    public function getFileByIdentifier(string $identifier): ?File
    {
        return $this->getFileByIdentifier->get($identifier);
    }

    /**
     * @return FileInstanceCollection
     *
     * @inheritDoc
     */
    public function getFilesByIdentifiers(array $identifiers): FileCollection
    {
        return $this->getFilesByIdentifiers->get($identifiers);
    }

    /**
     * @throws Exception
     */
    public function addFile(
        string $locationIdentifier,
        SplFileInfo $file,
        string $memberId = ''
    ): File {
        return $this->addFile->add(
            $locationIdentifier,
            $file,
            $memberId,
        );
    }

    /**
     * @throws Exception
     */
    public function uploadFile(
        string $locationIdentifier,
        SplFileInfo $file,
        ?string $subFolder = null
    ): SplFileInfo {
        return $this->uploadFile->upload(
            $locationIdentifier,
            $file,
            $subFolder,
        );
    }
}
