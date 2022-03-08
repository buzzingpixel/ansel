<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionItem;
use BuzzingPixel\Treasury\API\Locations as LocationsApi;
use BuzzingPixel\Treasury\Model\Locations as LocationsModel;
use BuzzingPixel\Treasury\Service\Data\Collection;

use function assert;

class GetLocationsTreasury implements GetLocationsContract
{
    private LocationsApi $treasuryLocationsApi;

    public function __construct(LocationsApi $treasuryLocationsApi)
    {
        $this->treasuryLocationsApi = $treasuryLocationsApi;
    }

    public function get(): LocationSelectionCollection
    {
        $treasuryLocations = $this->treasuryLocationsApi->getAllLocations();

        assert($treasuryLocations instanceof Collection);

        if ($treasuryLocations->count() < 1) {
            return new LocationSelectionCollection();
        }

        $locationItems = [];

        foreach ($treasuryLocations as $location) {
            assert($location instanceof LocationsModel);

            $locationItems[] = new LocationSelectionItem(
                /** @phpstan-ignore-next-line */
                'Treasury: ' . $location->name,
                /** @phpstan-ignore-next-line */
                'treasury:' . $location->handle,
            );
        }

        return new LocationSelectionCollection($locationItems);
    }
}
