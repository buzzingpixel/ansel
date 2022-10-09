<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use function in_array;
use function is_string;
use function mb_strtolower;

class TruthyValue
{
    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    public function isTruthy(): bool
    {
        $value = is_string($this->value) ?
            mb_strtolower($this->value) :
            $this->value;

        return in_array(
            $value,
            [
                true,
                1,
                '1',
                'y',
                'yes',
            ],
            true
        );
    }

    public function isNotTruthy(): bool
    {
        return ! $this->isTruthy();
    }
}
