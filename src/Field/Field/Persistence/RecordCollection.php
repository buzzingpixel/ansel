<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

use Exception;

use function array_filter;
use function array_keys;
use function array_map;
use function array_values;
use function count;

class RecordCollection
{
    /** @var Record[] $items */
    private array $items;

    private string $tableName;

    /**
     * @param Record[] $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values(array_map(
            static fn (Record $item) => $item,
            $items,
        ));

        if ($this->count() < 1) {
            return;
        }

        $tableName = $this->first()::tableName();

        $this->map(
            static function (Record $r) use ($tableName): void {
                if ($r::tableName() === $tableName) {
                    return;
                }

                throw new Exception(
                    'Table names of records in collection must match',
                );
            }
        );

        $this->tableName = $tableName;
    }

    public function tableName(): string
    {
        return $this->tableName;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @param callable(Record $adapterItem, int $index): ReturnType $callback
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
     * @return Record[]
     */
    public function asArray(): array
    {
        return $this->items;
    }

    /**
     * @param callable(Record $adapterItem): ReturnType $callable
     *
     * @return RecordCollection
     *
     * @template ReturnType
     */
    public function filter(callable $callable): self
    {
        return new self(array_values(array_filter(
            $this->asArray(),
            $callable,
        )));
    }

    public function first(): Record
    {
        return $this->items[0];
    }

    public function firstOrNull(): ?Record
    {
        return $this->items[0] ?? null;
    }

    /**
     * @param string[] $excludeProps
     *
     * @return array<array-key, scalar[]>
     */
    public function asScalarArray(array $excludeProps = []): array
    {
        return $this->map(static fn (Record $r) => $r->asArray(
            $excludeProps
        ));
    }
}
