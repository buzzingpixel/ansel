<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedFieldData
{
    private string $handle;

    /** @var float|int|string|bool|null */
    private $value;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['handle'] ?? ''),
            /** @phpstan-ignore-next-line */
            $arrayData['value'] ?? ''
        );
    }

    /**
     * @param float|int|string|bool $value
     */
    public function __construct(
        string $handle,
        $value
    ) {
        $this->handle = $handle;
        $this->value  = $value;
    }

    public function handle(): string
    {
        return $this->handle;
    }

    /**
     * @return float|int|string|bool|null
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return [
            'handle' => $this->handle(),
            'value' => $this->value(),
        ];
    }
}
