<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldRenderModel
{
    /** @var scalar[] */
    private array $fieldSettings;

    /** @var scalar[] */
    private array $customFields;

    /** @var scalar[] */
    private array $parameters;

    /** @var scalar[] */
    private array $translations;

    /**
     * @param scalar[] $fieldSettings
     * @param scalar[] $customFields
     * @param scalar[] $parameters
     * @param scalar[] $translations
     */
    public function __construct(
        array $fieldSettings,
        array $customFields,
        array $parameters,
        array $translations
    ) {
        $this->fieldSettings = $fieldSettings;
        $this->customFields  = $customFields;
        $this->parameters    = $parameters;
        $this->translations  = $translations;
    }

    /**
     * @return scalar[]
     */
    public function fieldSettings(): array
    {
        return $this->fieldSettings;
    }

    /**
     * @return scalar[]
     */
    public function customFields(): array
    {
        return $this->customFields;
    }

    /**
     * @return scalar[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return scalar[]
     */
    public function translations(): array
    {
        return $this->translations;
    }
}
