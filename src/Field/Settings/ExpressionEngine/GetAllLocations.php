<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;

class GetAllLocations
{
    private LocationGettersCollection $getters;

    public function __construct(LocationGettersCollection $getters)
    {
        $this->getters = $getters;
    }

    public function get(): LocationSelectionCollection
    {
        return $this->getters->getAll();
    }
}
