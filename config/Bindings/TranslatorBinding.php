<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Translations\TranslatorContract;
use BuzzingPixel\Ansel\Translations\TranslatorForCraft;
use BuzzingPixel\Ansel\Translations\TranslatorForEe;
use RuntimeException;

class TranslatorBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [TranslatorContract::class => TranslatorForEe::class];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [TranslatorContract::class => TranslatorForCraft::class];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
    }
}
