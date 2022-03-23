<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

interface GetUploadKeyContract
{
    public function get(): string;
}
