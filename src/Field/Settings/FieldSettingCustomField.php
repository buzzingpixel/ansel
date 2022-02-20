<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

class FieldSettingCustomField
{
    public const TYPE_TEXT = 'text';

    public const TYPE_BOOL = 'bool';

    public const TYPES = [
        self::TYPE_TEXT,
        self::TYPE_BOOL,
    ];

    private string $label;

    private string $type;

    private bool $required;

    public function __construct(
        string $label,
        string $type,
        bool $required
    ) {
        $this->label    = $label;
        $this->type     = $type;
        $this->required = $required;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function required(): bool
    {
        return $this->required;
    }
}
