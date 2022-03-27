<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads;

interface SaveNewUploadKeyContract
{
    public function save(UploadKeyRecord $record): void;
}
