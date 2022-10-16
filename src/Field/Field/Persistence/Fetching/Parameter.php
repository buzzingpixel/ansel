<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

interface Parameter
{
    public function property(): string;

    /**
     * @return scalar|scalar[]
     */
    public function value();

    public function operator(): Operator;
}
