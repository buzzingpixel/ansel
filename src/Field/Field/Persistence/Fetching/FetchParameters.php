<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

use function array_keys;
use function array_map;
use function array_merge;
use function array_values;

class FetchParameters
{
    /** @var Parameter[] */
    private array $items;

    private ?int $limit;

    /**
     * @param Parameter[] $items
     */
    public function __construct(
        array $items = [],
        ?int $limit = null
    ) {
        $this->items = array_values(array_map(
            static fn (Parameter $p) => $p,
            $items,
        ));

        $this->limit = $limit;
    }

    /**
     * @return Parameter[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function withItem(Parameter $parameter): self
    {
        $clone = clone $this;

        $clone->items[] = $parameter;

        return $clone;
    }

    /**
     * @param Parameter[] $items
     */
    public function withItems(array $items = []): self
    {
        $clone = clone $this;

        $clone->items = array_values(array_map(
            static fn (Parameter $p) => $p,
            $items,
        ));

        return $clone;
    }

    /**
     * @param Parameter[] $items
     */
    public function withAddedItems(array $items = []): self
    {
        $clone = clone $this;

        $newItems = array_values(array_map(
            static fn (Parameter $p) => $p,
            $items,
        ));

        $clone->items = array_values(array_merge(
            $clone->items,
            $newItems,
        ));

        return $clone;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function withLimit(?int $limit): self
    {
        $clone = clone $this;

        $clone->limit = $limit;

        return $clone;
    }

    /**
     * @param callable(Parameter $parameter, int $index): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array
    {
        $asArray = $this->asArray();

        return array_values(array_map(
            $callback,
            $asArray,
            array_keys($asArray),
        ));
    }

    /**
     * @return Parameter[]
     */
    public function asArray(): array
    {
        return $this->items;
    }
}
