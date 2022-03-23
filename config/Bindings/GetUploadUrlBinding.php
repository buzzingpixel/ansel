<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Uploads\GetUploadUrlContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\GetUploadUrlCraft;
use BuzzingPixel\Ansel\Field\Field\Uploads\GetUploadUrlEe;
use RuntimeException;

class GetUploadUrlBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [GetUploadUrlContract::class => GetUploadUrlEe::class];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [GetUploadUrlContract::class => GetUploadUrlCraft::class];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
    }
}
