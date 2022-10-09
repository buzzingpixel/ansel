<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use Exception;

use function is_string;
use function property_exists;

/**
 * @method self x(int $value)
 * @method self y(int $value)
 * @method self width(int $value)
 * @method self height(int $value)
 * @method self minWidth(int $value)
 * @method self minHeight(int $value)
 * @method self maxWidth(int $value)
 * @method self maxHeight(int $value)
 * @method self quality(int $value)
 * @method self optimize(int $value)
 */
class Parameters
{
    private ?int $x;

    private ?int $y;

    private ?int $width;

    private ?int $height;

    private ?int $minWidth;

    private ?int $minHeight;

    private ?int $maxWidth;

    private ?int $maxHeight;

    private ?OutputType $outputType;

    private ?int $quality;

    public function __construct(
        ?int $x = null,
        ?int $y = null,
        ?int $width = null,
        ?int $height = null,
        ?int $minWidth = null,
        ?int $minHeight = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?OutputType $outputType = null,
        ?int $quality = null
    ) {
        $this->x          = $x;
        $this->y          = $y;
        $this->width      = $width;
        $this->height     = $height;
        $this->minWidth   = $minWidth;
        $this->minHeight  = $minHeight;
        $this->maxWidth   = $maxWidth;
        $this->maxHeight  = $maxHeight;
        $this->outputType = $outputType;
        $this->quality    = $quality;
    }

    /**
     * @param mixed[] $arguments
     *
     * @throws Exception
     */
    public function __call(string $name, array $arguments): self
    {
        if (! property_exists($this, $name)) {
            throw new Exception($name . ' is not a valid property');
        }

        if (! isset($arguments[0])) {
            throw new Exception('A value must be provided');
        }

        $clone = clone $this;

        /** @phpstan-ignore-next-line */
        $clone->{$name} = $arguments[0];

        return $clone;
    }

    /**
     * @param string|OutputType $value
     */
    public function outputType($value): self
    {
        $clone = clone $this;

        if (is_string($value)) {
            $clone->outputType = new OutputType($value);

            return $clone;
        }

        $clone->outputType = $value;

        return $clone;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getMinWidth(): ?int
    {
        return $this->minWidth;
    }

    public function getMinHeight(): ?int
    {
        return $this->minHeight;
    }

    public function getMaxWidth(): ?int
    {
        return $this->maxWidth;
    }

    public function getMaxHeight(): ?int
    {
        return $this->maxHeight;
    }

    public function getOutputType(): ?OutputType
    {
        return $this->outputType;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }
}
