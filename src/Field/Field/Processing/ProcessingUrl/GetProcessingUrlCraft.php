<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Processing\ProcessingUrl;

use BuzzingPixel\Ansel\Shared\Facades\CraftUrlHelper;

class GetProcessingUrlCraft implements GetProcessingUrlContract
{
    private CraftUrlHelper $urlHelper;

    public function __construct(CraftUrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function get(): string
    {
        return $this->urlHelper->actionUrl('ansel/actions/process');
    }
}
