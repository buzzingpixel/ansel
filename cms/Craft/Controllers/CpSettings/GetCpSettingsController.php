<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\Controllers\CpSettings;

use BuzzingPixel\Ansel\Cp\Settings\Craft\Index\GetIndexAction;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\web\Controller;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;
use yii\web\Response;

use function assert;

/**
 * @codeCoverageIgnore
 */
class GetCpSettingsController extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        $container = (new ContainerManager())->container();

        $action = $container->get(GetIndexAction::class);

        assert($action instanceof GetIndexAction);

        $model = $action->render();

        return $this->renderTemplate(
            'ansel/Cp.twig',
            [
                'title' => $model->title(),
                'content' => $model->content(),
                'fullPageForm' => true,
            ],
        );
    }
}
