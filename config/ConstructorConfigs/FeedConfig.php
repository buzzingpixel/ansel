<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\ConstructorConfigs;

use BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval\RetrieveFeedFresh;
use BuzzingPixel\Container\ConstructorParamConfig;
use RuntimeException;

class FeedConfig
{
    /**
     * @return ConstructorParamConfig[]
     */
    public static function get(): array
    {
        $urlPrefix = 'https://www.buzzingpixel.com/software/';

        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [
                    new ConstructorParamConfig(
                        RetrieveFeedFresh::class,
                        'feedUrl',
                        $urlPrefix . 'ansel-ee/changelog/feed',
                    ),
                ];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [
                    new ConstructorParamConfig(
                        RetrieveFeedFresh::class,
                        'feedUrl',
                        $urlPrefix . 'ansel-craft/changelog/feed',
                    ),
                ];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException($msg . ANSEL_ENV);
        }
    }
}
