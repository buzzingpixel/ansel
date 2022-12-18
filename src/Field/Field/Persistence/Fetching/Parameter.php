<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

interface Parameter
{
    public function property(): string;

    /**
     * @return float|int|string|bool|array<(float|int|string|bool|null)>|null
     */
    public function value();

    public function operator(): Operator;
}
