<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

class LocationSelectionItem
{
    private string $label;

    private string $value;

    public function __construct(
        string $label,
        string $value
    ) {
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * @return string[]
     */
    public function asArray(): array
    {
        return [
            'label' => $this->label(),
            'value' => $this->value(),
        ];
    }

    public function label(): string
    {
        return $this->label;
    }

    public function value(): string
    {
        return $this->value;
    }
}
