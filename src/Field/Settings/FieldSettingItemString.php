<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

class FieldSettingItemString implements FieldSettingItemContract
{
    private string $key;

    private string $labelTranslationKey;

    private string $descriptionTranslationKey;

    private string $value;

    private bool $required;

    public function __construct(
        string $key,
        string $labelTranslationKey,
        string $descriptionTranslationKey,
        bool $required = false,
        string $value = ''
    ) {
        $this->key                       = $key;
        $this->labelTranslationKey       = $labelTranslationKey;
        $this->descriptionTranslationKey = $descriptionTranslationKey;
        $this->required                  = $required;
        $this->value                     = $value;
    }

    public function type(): string
    {
        return 'string';
    }

    public function key(): string
    {
        return $this->key;
    }

    public function labelTranslationKey(): string
    {
        return $this->labelTranslationKey;
    }

    public function descriptionTranslationKey(): string
    {
        return $this->descriptionTranslationKey;
    }

    public function required(): bool
    {
        return $this->required;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @phpstan-ignore-next-line
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
