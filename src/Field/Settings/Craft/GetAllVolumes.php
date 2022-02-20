<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionItem;
use craft\base\VolumeInterface;
use craft\services\Volumes;

use function array_map;

class GetAllVolumes
{
    private Volumes $volumesService;

    public function __construct(Volumes $volumesService)
    {
        $this->volumesService = $volumesService;
    }

    public function get(): LocationSelectionCollection
    {
        $volumeItems = array_map(
            static function (VolumeInterface $volume) {
                return new LocationSelectionItem(
                    (string) $volume->name,
                    (string) $volume->uid,
                );
            },
            $this->volumesService->getAllVolumes(),
        );

        return new LocationSelectionCollection($volumeItems);
    }
}
