<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Shared\SiteMeta;
use Craft;
use RuntimeException;

use Throwable;
use function rtrim;

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
                        $frontEndUrl = (string) ee()->config->item(
                            'site_url'
                        );

                        $frontEndUrl = rtrim(
                            $frontEndUrl,
                            '/'
                        );

                        $frontEndUrl .= '/' . ee()->config->item(
                            'site_index'
                        );

                        return new SiteMeta(
                            (int) ee()->config->item('site_id'),
                            $frontEndUrl
                        );

                    case 'craft':
                        try {
                            $sites = Craft::$app->getSites();

                            $craftSite = $sites->getCurrentSite();

                            $frontEndUrl = rtrim(
                                $craftSite->getBaseUrl(),
                                '/',
                            );

                            return new SiteMeta(
                                (int) $craftSite->id,
                                $frontEndUrl,
                            );
                        } catch (Throwable $e) {
                            return new SiteMeta(
                                0,
                                '',
                            );
                        }

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
