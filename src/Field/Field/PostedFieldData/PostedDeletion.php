<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedDeletion
{
    private string $uid;

    /**
     * @param mixed[] $postedDeletion
     */
    public static function fromArray(array $postedDeletion): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($postedDeletion['uid'] ?? ''),
        );
    }

    public function __construct(string $uid)
    {
        $this->uid = $uid;
    }

    public function uid(): string
    {
        return $this->uid;
    }
}
