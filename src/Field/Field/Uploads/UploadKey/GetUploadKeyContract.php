<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey;

interface GetUploadKeyContract
{
    public function get(): string;
}
