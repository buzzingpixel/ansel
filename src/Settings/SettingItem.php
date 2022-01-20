<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Settings;

use function base64_decode;
use function base64_encode;

class SettingItem
{
    public const TYPE_STRING = 'string';

    public const TYPE_INT = 'int';

    public const TYPE_BOOL = 'bool';

    private string $type;

    private string $key;

    private string $label;

    /** @var string|int|bool */
    private $value;

    private string $description;

    private bool $includeOnSettingsPage;

    /**
     * @param string|int|bool $value
     */
    public function __construct(
        string $type,
        string $key,
        string $label,
        $value,
        string $description,
        bool $includeOnSettingsPage
    ) {
        $this->type                  = $type;
        $this->key                   = $key;
        $this->label                 = $label;
        $this->value                 = $value;
        $this->description           = $description;
        $this->includeOnSettingsPage = $includeOnSettingsPage;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function label(): string
    {
        return $this->label;
    }

    /**
     * @return string|int|bool
     */
    public function value()
    {
        return $this->value;
    }

    public function getString(): string
    {
        return (string) $this->value;
    }

    public function getFromBase64(): string
    {
        return (string) base64_decode($this->getString(), true);
    }

    public function getInt(): int
    {
        return (int) $this->value;
    }

    /**
     * @param string|int|bool $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function setToBase64(string $value): void
    {
        $this->value = base64_encode($value);
    }

    public function description(): string
    {
        return $this->description;
    }

    public function includeOnSettingsPage(): bool
    {
        return $this->includeOnSettingsPage;
    }
}
