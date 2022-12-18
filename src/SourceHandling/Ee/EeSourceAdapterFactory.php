<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\SourceHandling\Ee;

use BuzzingPixel\Ansel\SourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterFactory;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterListCollection;
use BuzzingPixel\Ansel\SourceHandling\SourceAdapterListItem;
use ExpressionEngine\Core\Provider;
use ExpressionEngine\Service\Addon\Addon;
use ExpressionEngine\Service\Addon\Factory;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_filter;
use function array_map;
use function array_merge;
use function array_values;
use function assert;
use function is_array;

class EeSourceAdapterFactory implements SourceAdapterFactory
{
    private Factory $addonFactory;

    private ContainerInterface $container;

    public function __construct(
        Factory $addonFactory,
        ContainerInterface $container
    ) {
        $this->addonFactory = $addonFactory;
        $this->container    = $container;
    }

    public function listAllSourceAdapters(
        bool $enabledOnly = true
    ): SourceAdapterListCollection {
        $allAddons = $this->addonFactory->all();

        $sourceAdaptersIntermediate = array_values(array_map(
            static function (Addon $addon): array {
                $provider = $addon->getProvider();

                assert($provider instanceof Provider);

                /** @phpstan-ignore-next-line */
                $providerAnselConfig = $provider->get('ansel');

                if (! is_array($providerAnselConfig)) {
                    return [];
                }

                return $providerAnselConfig['sourceAdapters'] ?? [];
            },
            $allAddons,
        ));

        $sourceAdapters = array_merge(...$sourceAdaptersIntermediate);

        if ($enabledOnly) {
            $sourceAdapters = array_values(array_filter(
                $sourceAdapters,
                /**
                 * @param class-string<AnselSourceAdapter> $className
                 */
                static function (string $className): bool {
                    return $className::isEnabled();
                },
            ));
        }

        return new SourceAdapterListCollection(array_map(
            /**
             * @param class-string<AnselSourceAdapter> $className
             */
            static fn (string $className) => new SourceAdapterListItem(
                $className::getShortName(),
                $className::getDisplayName(),
                /** @phpstan-ignore-next-line */
                $className,
            ),
            $sourceAdapters,
        ));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createInstanceByShortName(
        string $shortName,
        bool $enabledOnly = true
    ): AnselSourceAdapter {
        $adapter = $this->listAllSourceAdapters($enabledOnly)
            ->getByShortName($shortName);

        if ($adapter === null) {
            throw new InvalidArgumentException(
                $shortName . ' is not a valid AnselSourceAdapter'
            );
        }

        /**
         * @var class-string<AnselSourceAdapter> $className
         * @noinspection PhpRedundantVariableDocTypeInspection
         */
        $className = $adapter->adapterClassName();

        $instance = $className::createInstance();

        if ($instance === null) {
            $instance = $this->container->get($className);
            assert($instance instanceof AnselSourceAdapter);
        }

        return $instance;
    }
}
