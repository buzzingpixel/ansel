<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use BuzzingPixel\Ansel\Cp\Settings\Ee\Index\GetIndexAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Index\PostIndexAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Updates\GetUpdatesAction;
use BuzzingPixel\AnselConfig\ContainerManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

class Ansel_mcp
{
    private ServerRequestInterface $request;

    private GetIndexAction $getIndexAction;

    private PostIndexAction $postIndexAction;

    private GetUpdatesAction $getUpdatesAction;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->request = $container->get(ServerRequestInterface::class);

        /** @phpstan-ignore-next-line */
        $this->getIndexAction = $container->get(GetIndexAction::class);

        /** @phpstan-ignore-next-line */
        $this->postIndexAction = $container->get(PostIndexAction::class);

        /** @phpstan-ignore-next-line */
        $this->getUpdatesAction = $container->get(GetUpdatesAction::class);
    }

    /**
     * @return string[]
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(): array
    {
        if (mb_strtolower($this->request->getMethod()) === 'post') {
            $this->indexPost();
        }

        return $this->indexGet();
    }

    /**
     * @return string[]
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function indexGet(): array
    {
        $model = $this->getIndexAction->render();

        return [
            'heading' => $model->heading(),
            'body' => $model->content(),
        ];
    }

    private function indexPost(): void
    {
        $this->postIndexAction->run($this->request);
    }

    /**
     * @return string[]
     */
    public function updates(): array
    {
        $model = $this->getUpdatesAction->render();

        return [
            'heading' => $model->heading(),
            'body' => $model->content(),
        ];
    }
}
