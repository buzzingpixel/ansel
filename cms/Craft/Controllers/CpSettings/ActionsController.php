<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\Controllers\CpSettings;

use BuzzingPixel\Ansel\Field\Field\Uploads\PostImageUploadAction;
use BuzzingPixel\Ansel\Shared\ActionInvokerEmitter;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\web\Controller;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use yii\base\InvalidConfigException;

class ActionsController extends Controller
{
    private ActionInvokerEmitter $actionInvokerEmitter;

    private PostImageUploadAction $postImageUploadAction;

    /**
     * @throws NotFoundExceptionInterface
     * @throws InvalidConfigException
     * @throws ContainerExceptionInterface
     */
    public function init(): void
    {
        $this->allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

        $this->enableCsrfValidation = false;

        parent::init();

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

    public function actionUpload(): void
    {
        $this->actionInvokerEmitter->invokeAndEmit(
            $this->postImageUploadAction,
        );
    }
}
