<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use CI_DB_forge;

class CIDBForge
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            CI_DB_forge::class => static function (): CI_DB_forge {
                /**
                 * Make sure the forge class is loaded
                 */
                ee()->load->dbforge();

                return ee()->dbforge;
            },
        ];
    }
}
