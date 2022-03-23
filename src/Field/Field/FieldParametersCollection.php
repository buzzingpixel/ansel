<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldParametersCollection
{
    private string $uploadUrl;

    private string $uploadKey;

    public function __construct(
        string $uploadUrl,
        string $uploadKey
    ) {
        $this->uploadUrl = $uploadUrl;
        $this->uploadKey = $uploadKey;
    }

    public function uploadUrl(): string
    {
        return $this->uploadUrl;
    }

    public function uploadKey(): string
    {
        return $this->uploadKey;
    }

    /**
     * @return scalar[]
     */
    public function asArray(): array
    {
        return [
            'uploadUrl' => $this->uploadUrl(),
            'uploadKey' => $this->uploadKey(),
        ];
    }
}
