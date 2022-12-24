<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\SourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\SourceHandling\Craft\Craft\CraftAddFile;
use BuzzingPixel\Ansel\SourceHandling\Craft\Craft\CraftGetFileByIdentifier;
use BuzzingPixel\Ansel\SourceHandling\Craft\Craft\CraftGetFilesByIdentifiers;
use BuzzingPixel\Ansel\SourceHandling\Craft\Craft\CraftStorageLocations;
use BuzzingPixel\Ansel\SourceHandling\File;
use BuzzingPixel\Ansel\SourceHandling\FileCollection;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationCollection;
use Exception;
use SplFileInfo;
use yii\base\InvalidConfigException;

use function dd;

class CraftSourceAdapter implements AnselSourceAdapter
{
    private CraftAddFile $addFile;

    private CraftStorageLocations $storageLocations;

    private CraftGetFileByIdentifier $getFileByIdentifier;

    private CraftGetFilesByIdentifiers $getFilesByIdentifiers;

    public function __construct(
        CraftAddFile $addFile,
        CraftStorageLocations $storageLocations,
        CraftGetFileByIdentifier $getFileByIdentifier,
        CraftGetFilesByIdentifiers $getFilesByIdentifiers
    ) {
        $this->addFile               = $addFile;
        $this->storageLocations      = $storageLocations;
        $this->getFileByIdentifier   = $getFileByIdentifier;
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
        return 'craft';
    }

    public static function getDisplayName(): string
    {
        return 'Craft';
    }

    public function getAllStorageLocations(): StorageLocationCollection
    {
        return $this->storageLocations->getAll();
    }

    /**
     * @throws Exception
     */
    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        throw new Exception('Not Implemented');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getFileByIdentifier(string $identifier): ?File
    {
        return $this->getFileByIdentifier->get($identifier);
    }

    /**
     * @throws InvalidConfigException
     *
     * @inheritDoc
     */
    public function getFilesByIdentifiers(array $identifiers): FileCollection
    {
        return $this->getFilesByIdentifiers->get($identifiers);
    }

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

    public function uploadFile(
        string $locationIdentifier,
        SplFileInfo $file,
        ?string $subFolder = null
    ): SplFileInfo {
        // TODO: Implement uploadFile() method.
        dd('TODO: Implement uploadFile() method.');
    }
}
