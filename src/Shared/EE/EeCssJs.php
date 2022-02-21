<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\EE;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Cp;

use function array_walk;
use function assert;
use function is_array;
use function json_decode;

class EeCssJs
{
    private Cp $cp;

    private string $pathThirdThemes;

    private string $urlThirdThemes;

    private InternalFunctions $internalFunctions;

    public function __construct(
        Cp $cp,
        string $pathThirdThemes,
        string $urlThirdThemes,
        InternalFunctions $internalFunctions
    ) {
        $this->cp                = $cp;
        $this->pathThirdThemes   = $pathThirdThemes;
        $this->urlThirdThemes    = $urlThirdThemes;
        $this->internalFunctions = $internalFunctions;
    }

    public function add(): void
    {
        $this->addCss();
        $this->addJs();
    }

    private function addCss(): void
    {
        $cssPath = $this->pathThirdThemes . '/ansel/css';

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

        array_walk(
            $cssManifest,
            function ($cssLoc): void {
                $css = $this->urlThirdThemes . '/ansel/css/' . $cssLoc;

                $cssTag = '<link rel="stylesheet" href="' . $css . '">';

                $this->cp->add_to_head($cssTag);
            }
        );
    }

    private function addJs(): void
    {
        $jsPath = $this->pathThirdThemes . '/ansel/js';

        $jsManifest = json_decode(
            $this->internalFunctions->fileGetContents(
                $jsPath . '/manifest.json',
            ),
            true,
        );

        assert(is_array($jsManifest));

        // Make sure main.js (our main JS) is last
        $mainJsLoc = $jsManifest['ansel.min.js'];
        unset($jsManifest['ansel.min.js']);
        $jsManifest['ansel.min.js'] = $mainJsLoc;

        array_walk(
            $jsManifest,
            function ($jsLoc): void {
                $js = $this->urlThirdThemes . '/ansel/js/' . $jsLoc;

                $jsTag = '<script type="text/javascript" src="' . $js . '">' .
                    '</script>';

                $this->cp->add_to_foot($jsTag);
            }
        );
    }
}
