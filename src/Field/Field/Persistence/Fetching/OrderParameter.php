<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

class OrderParameter implements Parameter
{
    private string $property;

    private Direction $value;

    public function __construct(
        string $property,
        Direction $value
    ) {
        $this->property = $property;
        $this->value    = $value;
    }

    public function property(): string
    {
        return $this->property;
    }

    public function value(): string
    {
        return $this->value->getValue();
    }

    public function operator(): Operator
    {
        return Operator::NONE();
    }
}
