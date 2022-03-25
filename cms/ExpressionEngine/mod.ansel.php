<?php

declare(strict_types=1);

use BuzzingPixel\Ansel\Field\Field\Uploads\PostImageUploadAction;
use BuzzingPixel\Ansel\Shared\ActionInvokerEmitter;
use BuzzingPixel\AnselConfig\ContainerManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch

/** @noinspection PhpIllegalPsrClassPathInspection */

class Ansel
{
    private ActionInvokerEmitter $actionInvokerEmitter;

    private PostImageUploadAction $postImageUploadAction;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->actionInvokerEmitter = $container->get(
            ActionInvokerEmitter::class,
        );

        /** @phpstan-ignore-next-line */
        $this->postImageUploadAction = $container->get(
            PostImageUploadAction::class,
        );
    }

    public function imageUploader(): void
    {
        $this->actionInvokerEmitter->invokeAndEmit(
            $this->postImageUploadAction,
        );
    }
}
