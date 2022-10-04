<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedFieldData
{
    private string $handle;

    /** @var mixed */
    private $value;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['handle'] ?? ''),
            $arrayData['value'] ?? ''
        );
    }

    /**
     * @param mixed $value
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
     * @return mixed
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
