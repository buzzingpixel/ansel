<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use craft\db\Query;

/**
 * @codeCoverageIgnore
 */
class CraftQueryBuilderFactory
{
    public function create(): Query
    {
        return new Query();
    }
}
