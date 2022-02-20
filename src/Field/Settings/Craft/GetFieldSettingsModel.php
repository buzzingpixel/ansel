<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

class GetFieldSettingsModel
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function content(): string
    {
        return $this->content;
    }
}
