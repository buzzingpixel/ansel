<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

class GetIndexModel
{
    private string $title;

    private string $content;

    public function __construct(
        string $title,
        string $content
    ) {
        $this->title   = $title;
        $this->content = $content;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }
}
