<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

class WhereParameter implements Parameter
{
    private string $property;

    /** @var float|int|string|bool|array<(float|int|string|bool|null)>|null */
    private $value;

    private Operator $operator;

    /**
     * @param float|int|string|bool|array<(float|int|string|bool|null)>|null $value
     */
    public function __construct(
        string $property,
        $value,
        ?Operator $operator = null
    ) {
        if ($operator === null) {
            $operator = Operator::EQUAL_TO();
        }

        $this->property = $property;
        $this->value    = $value;
        $this->operator = $operator;
    }

    public function property(): string
    {
        return $this->property;
    }

    /**
     * @inheritDoc
     */
    public function value()
    {
        return $this->value;
    }

    public function operator(): Operator
    {
        return $this->operator;
    }
}
