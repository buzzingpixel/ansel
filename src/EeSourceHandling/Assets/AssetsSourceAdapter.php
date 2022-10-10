<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling\Assets;

use BuzzingPixel\Ansel\EeSourceHandling\AnselSourceAdapter;
use BuzzingPixel\Ansel\EeSourceHandling\StorageLocationCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\AnselConfig\ContainerManager;
use ExpressionEngine\Service\Addon\Addon;
use ExpressionEngine\Service\Addon\Factory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;
use function dd;

class AssetsSourceAdapter implements AnselSourceAdapter
{
    private AssetsStorageLocations $assetsStorageLocations;

    public function __construct(AssetsStorageLocations $assetsStorageLocations)
    {
        $this->assetsStorageLocations = $assetsStorageLocations;
    }

    public static function createInstance(): ?self
    {
        return null;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function isEnabled(): bool
    {
        $container = (new ContainerManager())->container();

        $addonFactory = $container->get(Factory::class);

        assert($addonFactory instanceof Factory);

        $assets = $addonFactory->get('assets');

        /** @phpstan-ignore-next-line */
        assert($assets instanceof Addon || $assets === null);

        /** @phpstan-ignore-next-line */
        return $assets !== null && $assets->isInstalled();
    }

    public static function getShortName(): string
    {
        return 'assets';
    }

    public static function getDisplayName(): string
    {
        return 'Assets';
    }

    public function getAllStorageLocations(): StorageLocationCollection
    {
        return $this->assetsStorageLocations->getAll();
    }

    public function getModalLink(FieldSettingsCollection $fieldSettings): string
    {
        // TODO: Implement getModalLink() method.
        dd('TODO: Implement getModalLink() method.');
    }
}
