<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

class UploadKeyRecord
{
    public int $id = 0;

    public string $key = '';

    public int $expires = 0;
}
