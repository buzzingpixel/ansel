<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

class FieldSettingItemInteger implements FieldSettingItemContract
{
    private string $key;

    private string $labelTranslationKey;

    private string $descriptionTranslationKey;

    private bool $required;

    private int $value;

    private ?int $maxVal;

    private ?int $minVal;

    public function __construct(
        string $key,
        string $labelTranslationKey,
        string $descriptionTranslationKey,
        bool $required = false,
        int $value = 0,
        ?int $maxVal = null,
        ?int $minVal = null
    ) {
        $this->key                       = $key;
        $this->labelTranslationKey       = $labelTranslationKey;
        $this->descriptionTranslationKey = $descriptionTranslationKey;
        $this->required                  = $required;
        $this->maxVal                    = $maxVal;
        $this->minVal                    = $minVal;

        $this->setValue($value);
    }

    public function type(): string
    {
        return 'integer';
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

    public function value(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @phpstan-ignore-next-line
     */
    public function setValue($value): void
    {
        if ($this->maxVal !== null && $value > $this->maxVal) {
            $value = $this->maxVal;
        }

        if ($this->minVal !== null && $value < $this->minVal) {
            $value = $this->minVal;
        }

        $this->value = $value;
    }

    public function setValueFromString(string $value): void
    {
        $this->value = (int) $value;
    }

    public function isEmpty(): bool
    {
         return $this->value < 1;
    }
}
