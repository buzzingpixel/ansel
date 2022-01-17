<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Updates;

class GetUpdatesModel
{
    private string $heading;

    private string $content;

    public function __construct(
        string $heading,
        string $content
    ) {
        $this->heading = $heading;
        $this->content = $content;
    }

    public function heading(): string
    {
        return $this->heading;
    }

    public function content(): string
    {
        return $this->content;
    }
}
