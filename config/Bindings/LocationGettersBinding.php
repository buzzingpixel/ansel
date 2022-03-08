<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\LocationGettersCollection;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\LocationGettersCollectionFactory;
use Psr\Container\ContainerInterface;

use function assert;

class LocationGettersBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            LocationGettersCollection::class => static function (
                ContainerInterface $container
            ): LocationGettersCollection {
                $factory = $container->get(
                    LocationGettersCollectionFactory::class,
                );

                assert(
                    $factory instanceof LocationGettersCollectionFactory,
                );

                return $factory->make($container);
            },
        ];
    }
}
