<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Craft\AssetBundles;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AnselConfig\ContainerManager;
use BuzzingPixel\AssetsDist\AssetsDist;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_map;
use function array_values;
use function assert;
use function is_array;
use function json_decode;

class CraftAnselAssetBundle extends AssetBundle
{
    private InternalFunctions $internalFunctions;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @codeCoverageIgnore
     */
    public function init(): void
    {
        parent::init();

        $container = (new ContainerManager())->container();

        $internalFunctions = $container->get(InternalFunctions::class);

        assert($internalFunctions instanceof InternalFunctions);

        $this->internalFunctions = $internalFunctions;

        $this->registerBundle();
    }

    public function registerBundle(): void
    {
        $this->sourcePath = AssetsDist::getPath();

        $this->depends = [CpAsset::class];

        $this->addCss();
        $this->addJs();
    }

    private function addCss(): void
    {
        $cssPath = $this->sourcePath . '/css';

        $cssManifest = json_decode(
            $this->internalFunctions->fileGetContents(
                $cssPath . '/manifest.json',
            ),
            true,
        );

        assert(is_array($cssManifest));

        // Make sure style.min (our main CSS) is last
        $mainCssLoc = $cssManifest['ansel.min.css'];
        unset($cssManifest['ansel.min.css']);
        $cssManifest['ansel.min.css'] = $mainCssLoc;

        $this->css = array_values(array_map(
            static fn ($cssLoc) => 'css/' . $cssLoc,
            $cssManifest,
        ));
    }

    private function addJs(): void
    {
        $jsPath = $this->sourcePath . '/js';

        $jsManifest = json_decode(
            $this->internalFunctions->fileGetContents(
                $jsPath . '/manifest.json',
            ),
            true,
        );

        assert(is_array($jsManifest));

        // Make sure style.min (our main CSS) is last
        $mainJsLoc = $jsManifest['ansel.min.js'];
        unset($jsManifest['ansel.min.js']);
        $jsManifest['ansel.min.js'] = $mainJsLoc;

        $this->js = array_values(array_map(
            static fn ($jsLoc) => 'js/' . $jsLoc,
            $jsManifest,
        ));
    }
}
