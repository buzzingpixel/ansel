<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use BuzzingPixel\Ansel\Cp\Settings\Ee\Index\GetIndexAction;
use BuzzingPixel\AnselConfig\ContainerManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Ansel_mcp
{
    /**
     * @return string[]
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): array
    {
        $container = (new ContainerManager())->container();

        $action = $container->get(GetIndexAction::class);

        assert($action instanceof GetIndexAction);

        $model = $action->render();

        return [
            'heading' => $model->heading(),
            'body' => $model->content(),
        ];
    }
}
