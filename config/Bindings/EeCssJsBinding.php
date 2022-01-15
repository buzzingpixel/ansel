<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Cp;
use Psr\Container\ContainerInterface;

use function rtrim;

class EeCssJsBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EeCssJs::class => static function (ContainerInterface $container): EeCssJs {
                return new EeCssJs(
                    /** @phpstan-ignore-next-line */
                    $container->get(Cp::class),
                    /** @phpstan-ignore-next-line */
                    rtrim(PATH_THIRD_THEMES, '/'),
                    /** @phpstan-ignore-next-line */
                    rtrim(URL_THIRD_THEMES, '/'),
                    /** @phpstan-ignore-next-line */
                    $container->get(InternalFunctions::class),
                );
            },
        ];
    }
}
