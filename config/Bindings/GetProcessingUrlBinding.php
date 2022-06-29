<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Processing\ProcessingUrl\GetProcessingUrlContract;
use BuzzingPixel\Ansel\Field\Field\Processing\ProcessingUrl\GetProcessingUrlCraft;
use BuzzingPixel\Ansel\Field\Field\Processing\ProcessingUrl\GetProcessingUrlEe;
use RuntimeException;

class GetProcessingUrlBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [GetProcessingUrlContract::class => GetProcessingUrlEe::class];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [GetProcessingUrlContract::class => GetProcessingUrlCraft::class];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
    }
}
