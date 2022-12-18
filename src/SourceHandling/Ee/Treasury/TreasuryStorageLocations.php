<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Treasury;

use BuzzingPixel\Ansel\SourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationItem;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationItemCollection;
use BuzzingPixel\Treasury\API\Locations as LocationsApi;
use BuzzingPixel\Treasury\Model\Locations as LocationsModel;
use BuzzingPixel\Treasury\Service\Data\Collection;

use function assert;

class TreasuryStorageLocations
{
    private LocationsApi $treasuryLocationsApi;

    public function __construct(LocationsApi $treasuryLocationsApi)
    {
        $this->treasuryLocationsApi = $treasuryLocationsApi;
    }

    public function getAll(): StorageLocationCollection
    {
        $treasuryLocations = $this->treasuryLocationsApi->getAllLocations();

        assert($treasuryLocations instanceof Collection);

        if ($treasuryLocations->count() < 1) {
            return new StorageLocationItemCollection();
        }

        $locationItems = [];

        foreach ($treasuryLocations as $location) {
            assert($location instanceof LocationsModel);

            $locationItems[] = new StorageLocationItem(
                /** @phpstan-ignore-next-line */
                (string) $location->handle,
                /** @phpstan-ignore-next-line */
                (string) $location->name
            );
        }

        return new StorageLocationItemCollection($locationItems);
    }
}
