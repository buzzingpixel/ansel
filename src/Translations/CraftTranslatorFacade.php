<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Translations;

use Craft;

/**
 * @codeCoverageIgnore
 */
class CraftTranslatorFacade
{
    public function translate(string $key): string
    {
        return Craft::t('ansel', $key);
    }
}
