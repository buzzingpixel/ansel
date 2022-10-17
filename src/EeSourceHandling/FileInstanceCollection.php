<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_values;

class FileInstanceCollection implements FileCollection
{
    /** @var FileInstance[] */
    private array $items;

    /**
     * @param FileInstance[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (FileInstance $file) => $file,
            $items,
        ));
    }

    /**
     * @return FileInstance[]
     */
    public function asArray(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
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

    public function filter(callable $callback): self
    {
        return new self(array_values(array_filter(
            $this->asArray(),
            $callback,
        )));
    }

    /**
     * @param self $files
     *
     * @phpstan-ignore-next-line
     */
    public function withAddedFiles(FileCollection $files): self
    {
        return new self(array_merge(
            $this->asArray(),
            $files->asArray(),
        ));
    }

    public function first(): File
    {
        return $this->items[0];
    }

    public function firstOrNull(): ?File
    {
        return $this->items[0] ?? null;
    }
}
