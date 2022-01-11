<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetIndexAction
{
    private TwigEnvironment $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(): GetIndexModel
    {
        return new GetIndexModel(
            'Ansel Settings',
            $this->twig->render(
                '@AnselSrc/Cp/Settings/Craft/Index/Index.twig',
            ),
        );
    }
}
