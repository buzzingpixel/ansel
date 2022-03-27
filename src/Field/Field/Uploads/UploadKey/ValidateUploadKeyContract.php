<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey;

interface ValidateUploadKeyContract
{
    public function validate(string $key): bool;
}
