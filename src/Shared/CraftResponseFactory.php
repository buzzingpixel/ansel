<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use Craft;
use craft\web\Response;

/**
 * @codeCoverageIgnore
 */
class CraftResponseFactory
{
    public function getResponse(): Response
    {
        /** @phpstan-ignore-next-line */
        return Craft::$app->getResponse();
    }
}
