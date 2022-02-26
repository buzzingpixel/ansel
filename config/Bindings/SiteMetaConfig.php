<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Shared\EE\SiteMeta;
use RuntimeException;

class SiteMetaConfig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            SiteMeta::class => static function (): SiteMeta {
                switch (ANSEL_ENV) {
                    /** @phpstan-ignore-next-line */
                    case 'ee':
                        return new SiteMeta(
                            (int) ee()->config->item('site_id'),
                        );

                    default:
                        $msg = 'Class is not implemented for platform ';

                        throw new RuntimeException(
                            $msg . ANSEL_ENV,
                        );
                }
            },
        ];
    }
}
