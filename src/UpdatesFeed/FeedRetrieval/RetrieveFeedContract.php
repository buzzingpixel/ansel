<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval;

interface RetrieveFeedContract
{
    public function retrieve(): string;
}
