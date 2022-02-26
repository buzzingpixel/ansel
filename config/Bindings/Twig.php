<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\AnselSrc;
use BuzzingPixel\Ansel\Shared\CraftTwigLoader;
use BuzzingPixel\AnselConfig\AnselConfig;
use buzzingpixel\twigswitch\SwitchTwigExtension;
use Craft;
use craft\web\twig\TemplateLoader;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

use function assert;

class Twig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            FilesystemLoader::class => static function (): FilesystemLoader {
                $loader = new FilesystemLoader();

                $loader->addPath(
                    AnselConfig::getPath(),
                    'AnselConfig',
                );

                $loader->addPath(
                    AnselSrc::getPath(),
                    'AnselSrc',
                );

                return $loader;
            },
            TwigEnvironment::class => static function (
                ContainerInterface $container
            ): TwigEnvironment {
                $filesystemLoader = $container->get(FilesystemLoader::class);

                assert($filesystemLoader instanceof FilesystemLoader);

                switch (ANSEL_ENV) {
                    /** @phpstan-ignore-next-line */
                    case 'ee':
                        $twig = new TwigEnvironment(
                            $filesystemLoader,
                            [
                                'debug' => true,
                                'strict_variables' => true,
                            ],
                        );

                        $twig->addExtension(
                            new SwitchTwigExtension(),
                        );

                        return $twig;

                    /** @phpstan-ignore-next-line */
                    case 'craft':
                        /** @phpstan-ignore-next-line */
                        $twig = Craft::$app->getView()->getTwig();

                        $craftLoader = $twig->getLoader();

                        assert(
                            $craftLoader instanceof TemplateLoader,
                        );

                        $twig->setLoader(
                            new CraftTwigLoader(
                                $filesystemLoader,
                                $craftLoader,
                            ),
                        );

                        return $twig;

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
