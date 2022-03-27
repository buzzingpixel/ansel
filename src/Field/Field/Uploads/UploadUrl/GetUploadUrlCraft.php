<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadUrl;

use BuzzingPixel\Ansel\Shared\Facades\CraftUrlHelper;

class GetUploadUrlCraft implements GetUploadUrlContract
{
    private CraftUrlHelper $urlHelper;

    public function __construct(CraftUrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function get(): string
    {
        return $this->urlHelper->actionUrl('ansel/actions/upload');
    }
}
