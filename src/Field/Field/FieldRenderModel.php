<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldRenderModel
{
    /** @var array<(float|int|string|bool)> */
    private array $fieldSettings;

    /** @var array<(float|int|string|bool)> */
    private array $customFields;

    /** @var array<(float|int|string|bool)> */
    private array $parameters;

    /** @var array<(float|int|string|bool)> */
    private array $translations;

    /** @var array<(float|int|string|bool)> */
    private array $platform;

    /** @var mixed[] */
    private array $data;

    /**
     * @param array<(float|int|string|bool)> $fieldSettings
     * @param array<(float|int|string|bool)> $customFields
     * @param array<(float|int|string|bool)> $parameters
     * @param array<(float|int|string|bool)> $translations
     * @param array<(float|int|string|bool)> $platform
     * @param mixed[]                        $data
     */
    public function __construct(
        array $fieldSettings,
        array $customFields,
        array $parameters,
        array $translations,
        array $platform = [],
        array $data = []
    ) {
        $this->fieldSettings = $fieldSettings;
        $this->customFields  = $customFields;
        $this->parameters    = $parameters;
        $this->translations  = $translations;
        $this->platform      = $platform;
        $this->data          = $data;
    }

    /**
     * @return array<(float|int|string|bool)>
     */
    public function fieldSettings(): array
    {
        return $this->fieldSettings;
    }

    /**
     * @return array<(float|int|string|bool)>
     */
    public function customFields(): array
    {
        return $this->customFields;
    }

    /**
     * @return array<(float|int|string|bool)>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array<(float|int|string|bool)>
     */
    public function translations(): array
    {
        return $this->translations;
    }

    /**
     * @return array<(float|int|string|bool)>
     */
    public function platform(): array
    {
        return $this->platform;
    }

    /**
     * @return mixed[]
     */
    public function data(): array
    {
        return $this->data;
    }
}
