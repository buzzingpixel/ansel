<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Field\Uploads\GetUploadKey;
use BuzzingPixel\Ansel\Field\Field\Uploads\GetUploadKeyContract;

class GetUploadKeyBinding
{
    /**
     * @return string[]
     */
    public static function get(): array
    {
        return [GetUploadKeyContract::class => GetUploadKey::class];
    }
}
