<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;

use function array_map;
use function array_merge;
use function array_values;

class LocationGettersCollection
{
    /** @var GetLocationsContract[] */
    private array $getters;

    /**
     * @param GetLocationsContract[] $getters
     */
    public function __construct(array $getters = [])
    {
        $this->getters = array_values(array_map(
            static fn (GetLocationsContract $c) => $c,
            $getters,
        ));
    }

    public function getAll(): LocationSelectionCollection
    {
        $collection = new LocationSelectionCollection();

        foreach ($this->getters as $getter) {
            $collection = $collection->add($getter->get());
        }

        return $collection;
    }

    public function withGetter(GetLocationsContract $getter): self
    {
        return new self(array_values(array_merge(
            $this->getters,
            [$getter],
        )));
    }
}
