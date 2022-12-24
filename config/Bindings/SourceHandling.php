<?php


declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\SourceHandling\Craft\CraftSourceAdapterFactory;
use BuzzingPixel\Ansel\SourceHandling\Ee\EeSourceAdapterFactory;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterFactory;
use Psr\Container\ContainerInterface;
use RuntimeException;

class SourceHandling
{
    public static function get(): array
    {
        return [
            SourceAdapterFactory::class => static function (
                ContainerInterface $container
            ): SourceAdapterFactory {
                switch (ANSEL_ENV) {
                    case 'ee':
                        return $container->get(EeSourceAdapterFactory::class);

                    case 'craft':
                        return $container->get(CraftSourceAdapterFactory::class);

                    default:
                        $msg = 'Class is not implemented for platform ';

                        throw new RuntimeException(
                            $msg . ANSEL_ENV,
                        );
                }
            }
        ];
    }
}
