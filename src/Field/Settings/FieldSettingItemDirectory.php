<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function explode;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

class FieldSettingItemDirectory implements FieldSettingItemContract
{
    private string $key;

    private string $labelTranslationKey;

    private string $descriptionTranslationKey;

    private bool $required;

    private string $value;

    private string $directoryType = '';

    private string $directoryId = '';

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

        $this->setValue($value);
    }

    public function type(): string
    {
        return 'directory';
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
        $split = explode(':', $value);

        $this->directoryType = $split[0] ?? '';

        $this->directoryId = $split[1] ?? '';

        $this->value = $value;
    }

    public function setValueFromString(string $value): void
    {
        $split = explode(':', $value);

        $this->directoryType = $split[0] ?? '';

        $this->directoryId = $split[1] ?? '';

        if ($this->directoryId === '') {
            $this->directoryId = $this->directoryType;

            $this->directoryType = 'n/a';
        }

        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function directoryType(): string
    {
        return $this->directoryType;
    }

    public function directoryId(): string
    {
        return $this->directoryId;
    }
}
