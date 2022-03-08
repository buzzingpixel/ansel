<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use ExpressionEngine\Service\Addon\Addon;
use ExpressionEngine\Service\Addon\Factory;
use Psr\Container\ContainerInterface;

use function assert;

class LocationGettersCollectionFactory
{
    private Factory $addonFactory;

    public function __construct(Factory $addonFactory)
    {
        $this->addonFactory = $addonFactory;
    }

    public function make(
        ContainerInterface $container
    ): LocationGettersCollection {
        $collection = new LocationGettersCollection(
        /** @phpstan-ignore-next-line */
            [$container->get(GetLocationsEe::class)],
        );

        $treasury = $this->addonFactory->get('treasury');

        /** @phpstan-ignore-next-line */
        assert($treasury instanceof Addon || $treasury === null);

        /** @phpstan-ignore-next-line */
        if ($treasury !== null && $treasury->isInstalled()) {
            $treasuryGetter = $container->get(
                GetLocationsTreasury::class,
            );

            assert($treasuryGetter instanceof GetLocationsContract);

            $collection = $collection->withGetter($treasuryGetter);
        }

        $assets = $this->addonFactory->get('assets');

        /** @phpstan-ignore-next-line */
        if ($assets !== null && $assets->isInstalled()) {
            $assetsGetter = $container->get(GetLocationsAssets::class);

            assert($assetsGetter instanceof GetLocationsAssets);

            $collection = $collection->withGetter($assetsGetter);
        }

        return $collection;
    }
}
