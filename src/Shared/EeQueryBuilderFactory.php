<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use ExpressionEngine\Service\Database\Query;

/**
 * @codeCoverageIgnore
 */
class EeQueryBuilderFactory
{
    public function create(): Query
    {
        /** @phpstan-ignore-next-line */
        return ee('db');
    }
}
