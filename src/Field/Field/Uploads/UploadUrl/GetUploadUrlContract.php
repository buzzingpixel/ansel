<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadUrl;

interface GetUploadUrlContract
{
    public function get(): string;
}
