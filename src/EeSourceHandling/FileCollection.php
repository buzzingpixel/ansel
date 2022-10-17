<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

interface FileCollection
{
    /**
     * @return File[]
     */
    public function asArray(): array;

    /**
     * @param callable(File $file): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array;

    /**
     * @param callable(File $file): ReturnType $callback
     *
     * @template ReturnType
     */
    public function filter(callable $callback): FileCollection;

    public function withAddedFiles(FileCollection $files): FileCollection;

    public function first(): File;

    public function firstOrNull(): ?File;
}
