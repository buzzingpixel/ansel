<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Craft;
use craft\db\Connection as DbConnection;

class CraftDbConnection
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            DbConnection::class => static function (): DbConnection {
                /** @phpstan-ignore-next-line */
                return Craft::$app->getDb();
            },
        ];
    }
}
