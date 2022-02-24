<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint

interface FieldSettingItemContract
{
    public function type(): string;

    public function key(): string;

    public function labelTranslationKey(): string;

    public function descriptionTranslationKey(): string;

    /** @phpstan-ignore-next-line */
    public function value();

    /** @phpstan-ignore-next-line */
    public function setValue($value): void;

    public function setValueFromString(string $value): void;

    public function required(): bool;

    public function isEmpty(): bool;
}
