<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\LocationSelectionCollection;

interface GetLocationsContract
{
    public function get(): LocationSelectionCollection;
}
