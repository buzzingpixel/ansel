<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

interface GetUploadUrlContract
{
    public function get(): string;
}
