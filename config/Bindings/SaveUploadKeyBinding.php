<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\SaveNewUploadKeyContract;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\SaveNewUploadKeyCraft;
use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\SaveNewUploadKeyEe;
use RuntimeException;

class SaveUploadKeyBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [SaveNewUploadKeyContract::class => SaveNewUploadKeyEe::class];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [SaveNewUploadKeyContract::class => SaveNewUploadKeyCraft::class];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
    }
}
