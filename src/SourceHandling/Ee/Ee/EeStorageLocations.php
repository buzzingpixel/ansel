<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee\Ee;

use BuzzingPixel\Ansel\Shared\SiteMeta;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationItem;
use BuzzingPixel\Ansel\SourceHandling\StorageLocationItemCollection;
use ExpressionEngine\Model\File\UploadDestination;
use ExpressionEngine\Service\Model\Collection;
use ExpressionEngine\Service\Model\Facade as RecordService;

use function assert;

class EeStorageLocations
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

    public function getAll(): StorageLocationItemCollection
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

        $items = $eeLocations->map(
            static fn (UploadDestination $location) => new StorageLocationItem(
                (string) $location->getId(),
                /** @phpstan-ignore-next-line */
                (string) $location->getProperty('name'),
            ),
        );

        return new StorageLocationItemCollection($items);
    }
}
