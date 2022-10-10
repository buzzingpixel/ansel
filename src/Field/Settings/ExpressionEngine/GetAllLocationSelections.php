<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\EeSourceHandling\SourceAdapterFactory;
use BuzzingPixel\Ansel\EeSourceHandling\SourceAdapterListItem;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocation;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;
use BuzzingPixel\Ansel\Field\Settings\LocationSelectionItem;

class GetAllLocationSelections
{
    private SourceAdapterFactory $sourceAdapterFactory;

    public function __construct(SourceAdapterFactory $sourceAdapterFactory)
    {
        $this->sourceAdapterFactory = $sourceAdapterFactory;
    }

    public function get(): LocationSelectionCollection
    {
        $collection = new LocationSelectionCollection();

        $this->sourceAdapterFactory->listAllSourceAdapters()->map(
            function (SourceAdapterListItem $adapterItem) use (
                &$collection
            ): void {
                $adapter = $this->sourceAdapterFactory->createInstanceByShortName(
                    $adapterItem->shortName(),
                );

                $locations = $adapter->getAllStorageLocations();

                $collection = $collection->add(
                    new LocationSelectionCollection(
                        $locations->map(
                            static fn (StorageLocation $l) => new LocationSelectionItem(
                                $adapter::getDisplayName() . ': ' . $l->displayName(),
                                $adapter::getShortName() . ':' . $l->id()
                            ),
                        )
                    )
                );
            }
        );

        return $collection;
    }
}
