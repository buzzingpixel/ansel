<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionItem;
use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Collection;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class GetAllLocations
{
    private SiteMeta $siteMeta;
    private RecordService $recordService;

    public function __construct(
        SiteMeta $siteMeta,
        RecordService $recordService
    ) {
        $this->siteMeta      = $siteMeta;
        $this->recordService = $recordService;
    }

    public function get(): LocationSelectionCollection
    {
        $eeLocations = $this->recordService->get('UploadDestination')
            // Only get upload directories for the current site
            ->filter('site_id', $this->siteMeta->siteId())
            // Filter system directories out of records
            ->filter('module_id', 0)
            // Order alphabetically
            ->order('name', 'asc')
            ->all();

        assert($eeLocations instanceof Collection);

        $locationItems = $eeLocations->map(
            static function (
                UploadDestination $location
            ): LocationSelectionItem {
                return new LocationSelectionItem(
                    /** @phpstan-ignore-next-line */
                    'EE: ' . $location->getProperty('name'),
                    (string) $location->getId(),
                );
            }
        );

        // TODO: Add in support for the other storage location types

        return new LocationSelectionCollection($locationItems);
    }
}
