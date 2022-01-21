<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee;

class McpModel
{
    private string $heading;

    private string $body;

    public function __construct(
        string $heading,
        string $body
    ) {
        $this->heading = $heading;
        $this->body    = $body;
    }

    public function heading(): string
    {
        return $this->heading;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'heading' => $this->heading(),
            'body' => $this->body(),
        ];
    }
}
