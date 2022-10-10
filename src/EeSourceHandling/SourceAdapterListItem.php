<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

class SourceAdapterListItem
{
    private string $shortName;

    private string $displayName;

    /** @var class-string<AnselSourceAdapter> */
    private string $adapterClassName;

    /**
     * @param class-string<AnselSourceAdapter> $adapterClassName
     */
    public function __construct(
        string $shortName,
        string $displayName,
        string $adapterClassName
    ) {
        $this->shortName        = $shortName;
        $this->displayName      = $displayName;
        $this->adapterClassName = $adapterClassName;
    }

    public function shortName(): string
    {
        return $this->shortName;
    }

    public function displayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return class-string<AnselSourceAdapter>
     */
    public function adapterClassName(): string
    {
        return $this->adapterClassName;
    }
}
