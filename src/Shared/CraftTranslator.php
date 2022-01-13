<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use Craft;

/**
 * @codeCoverageIgnore
 */
class CraftTranslator
{
    public function translate(string $key): string
    {
        return Craft::t('ansel', $key);
    }
}
