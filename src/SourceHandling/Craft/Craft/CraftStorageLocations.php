<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Craft\Craft;

use BuzzingPixel\Ansel\SourceHandling\StorageLocationItem;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationItemCollection;
use craft\base\VolumeInterface;
use craft\services\Volumes;

use function array_map;

class CraftStorageLocations
{
    private Volumes $volumes;

    public function __construct(Volumes $volumes)
    {
        $this->volumes = $volumes;
    }

    public function getAll(): StorageLocationItemCollection
    {
        $volumes = $this->volumes->getAllVolumes();

        $items = array_map(
            static fn (VolumeInterface $volume) => new StorageLocationItem(
                (string) $volume->uid,
                (string) $volume->name,
            ),
            $volumes,
        );

        return new StorageLocationItemCollection($items);
    }
}
