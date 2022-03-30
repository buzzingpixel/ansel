<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

class FieldParametersCollection
{
    private string $environment;

    private string $uploadUrl;

    private string $uploadKey;

    public function __construct(
        string $environment,
        string $uploadUrl,
        string $uploadKey
    ) {
        $this->environment = $environment;
        $this->uploadUrl   = $uploadUrl;
        $this->uploadKey   = $uploadKey;
    }

    public function environment(): string
    {
        return $this->environment;
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
            'environment' => $this->environment(),
            'uploadUrl' => $this->uploadUrl(),
            'uploadKey' => $this->uploadKey(),
        ];
    }
}
