<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

use BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey\GetUploadKeyContract;

use function dd;

class GetUploadKeyCraft implements GetUploadKeyContract
{
    public function get(): string
    {
        // TODO: Implement get() method.
        dd('GetUploadKeyCraft');
    }
}
