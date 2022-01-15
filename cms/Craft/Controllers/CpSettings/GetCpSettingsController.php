<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\Controllers\CpSettings;

use BuzzingPixel\Ansel\Cp\Settings\Craft\Index\GetIndexAction;
use BuzzingPixel\Ansel\Cp\Settings\Craft\Index\PostIndexAction;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\web\Controller;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;
use yii\web\Response;

use function mb_strtolower;

/**
 * @codeCoverageIgnore
 */
class GetCpSettingsController extends Controller
{
    private ServerRequestInterface $requestInterface;

    private GetIndexAction $getIndexAction;

    private PostIndexAction $postIndexAction;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->requestInterface = $container->get(ServerRequestInterface::class);

        /** @phpstan-ignore-next-line */
        $this->getIndexAction = $container->get(GetIndexAction::class);

        /** @phpstan-ignore-next-line */
        $this->postIndexAction = $container->get(PostIndexAction::class);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        if (mb_strtolower($this->requestInterface->getMethod()) === 'post') {
            return $this->indexPost();
        }

        return $this->indexGet();
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     */
    private function indexGet(): Response
    {
        $model = $this->getIndexAction->render();

        return $this->renderTemplate(
            'ansel/Cp.twig',
            [
                'title' => $model->title(),
                'content' => $model->content(),
                'fullPageForm' => true,
            ],
        );
    }

    private function indexPost(): Response
    {
        return $this->postIndexAction->run($this->requestInterface);
    }
}
