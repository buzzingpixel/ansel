<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\ValidateUploadKeyContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\ValidateUploadKeyCraft;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\ValidateUploadKeyEe;
use RuntimeException;

class ValidateUploadKeyBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [ValidateUploadKeyContract::class => ValidateUploadKeyEe::class];

                /** @phpstan-ignore-next-line */
            case 'craft':
                return [ValidateUploadKeyContract::class => ValidateUploadKeyCraft::class];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
    }
}
