<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Assets;

use Assets_lib;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationItem;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationItemCollection;

use function count;

class AssetsStorageLocations
{
    /** @phpstan-ignore-next-line */
    private Assets_lib $assetsLib;

    /** @phpstan-ignore-next-line */
    public function __construct(Assets_lib $assetsLib)
    {
        $this->assetsLib = $assetsLib;
    }

    public function getAll(): StorageLocationCollection
    {
        /** @phpstan-ignore-next-line */
        $assetsLocations = $this->assetsLib->get_all_sources();

        if (count($assetsLocations) < 1) {
            return new StorageLocationItemCollection();
        }

        $locationItems = [];

        foreach ($assetsLocations as $assetsSource) {
            $locationItems[] = new StorageLocationItem(
                (string) $assetsSource->id,
                (string) $assetsSource->name,
            );
        }

        return new StorageLocationItemCollection($locationItems);
    }
}
