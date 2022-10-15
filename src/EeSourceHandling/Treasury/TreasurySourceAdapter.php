<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Treasury;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\EeSourceHandling\File;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\AnselConfig\ContainerManager;
use ExpressionEngine\Service\Addon\Addon;
use ExpressionEngine\Service\Addon\Factory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SplFileInfo;

use function assert;
use function dd;

class TreasurySourceAdapter implements AnselSourceAdapter
{
    private TreasuryStorageLocations $treasuryStorageLocations;

    public function __construct(
        TreasuryStorageLocations $treasuryStorageLocations
    ) {
        $this->treasuryStorageLocations = $treasuryStorageLocations;
    }

    public static function createInstance(): ?self
    {
        return null;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function isEnabled(): bool
    {
        $container = (new ContainerManager())->container();

        $addonFactory = $container->get(Factory::class);

        assert($addonFactory instanceof Factory);

        $treasury = $addonFactory->get('treasury');

        /** @phpstan-ignore-next-line */
        assert($treasury instanceof Addon || $treasury === null);

        /** @phpstan-ignore-next-line */
        return $treasury !== null && $treasury->isInstalled();
    }

    public static function getShortName(): string
    {
        return 'treasury';
    }

    public static function getDisplayName(): string
    {
        return 'Treasury';
    }

    public function getAllStorageLocations(): StorageLocationCollection
    {
        return $this->treasuryStorageLocations->getAll();
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        // TODO: Implement getModalLink() method.
        dd('TODO: Implement getModalLink() method.');
    }

    public function getFileByIdentifier(string $identifier): File
    {
        // TODO: Implement getFileByIdentifier() method.
        dd('TODO: Implement getFileByIdentifier() method.');
    }

    public function addFile(
        string $locationIdentifier,
        SplFileInfo $file,
        string $memberId = ''
    ): File {
        // TODO: Implement addFile() method.
        dd('TODO: Implement addFile() method.');
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
