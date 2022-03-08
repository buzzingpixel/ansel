<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use Assets_lib;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionItem;

use function count;

class GetLocationsAssets implements GetLocationsContract
{
    /** @phpstan-ignore-next-line */
    private Assets_lib $assetsLib;

    /** @phpstan-ignore-next-line */
    public function __construct(Assets_lib $assetsLib)
    {
        $this->assetsLib = $assetsLib;
    }

    public function get(): LocationSelectionCollection
    {
        /** @phpstan-ignore-next-line */
        $assetsLocations = $this->assetsLib->get_all_sources();

        if (count($assetsLocations) < 1) {
            return new LocationSelectionCollection();
        }

        $locationItems = [];

        foreach ($assetsLocations as $assetsSource) {
            $locationItems[] = new LocationSelectionItem(
                'Assets: ' . $assetsSource->name,
                'assets:' . $assetsSource->id,
            );
        }

        return new LocationSelectionCollection($locationItems);
    }
}
