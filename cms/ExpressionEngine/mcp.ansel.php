<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use BuzzingPixel\Ansel\Cp\Settings\Ee\Index\GetIndexAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Index\PostIndexAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\License\GetLicenseAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\License\PostLicenseAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Updates\GetUpdatesAction;
use BuzzingPixel\Ansel\License\EeLicenseBanner;
use BuzzingPixel\Ansel\License\LicenseStatus;
use BuzzingPixel\AnselConfig\ContainerManager;
use GuzzleHttp\Exception\GuzzleException;
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

    private GetLicenseAction $getLicenseAction;

    private PostLicenseAction $postLicenseAction;

    private LicenseStatus $licenseStatus;

    private EeLicenseBanner $eeLicenseBanner;

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

        /** @phpstan-ignore-next-line */
        $this->getLicenseAction = $container->get(GetLicenseAction::class);

        /** @phpstan-ignore-next-line */
        $this->postLicenseAction = $container->get(PostLicenseAction::class);

        /** @phpstan-ignore-next-line */
        $this->licenseStatus = $container->get(LicenseStatus::class);

        /** @phpstan-ignore-next-line */
        $this->eeLicenseBanner = $container->get(EeLicenseBanner::class);
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
        $this->eeLicenseBanner->setFromLicenseResult(
            $this->licenseStatus->get(),
        );

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
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updates(): array
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            $this->licenseStatus->get(),
        );

        $model = $this->getUpdatesAction->render();

        return [
            'heading' => $model->heading(),
            'body' => $model->content(),
        ];
    }

    /**
     * @return string[]
     *
     * @throws GuzzleException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function license(): array
    {
        if (mb_strtolower($this->request->getMethod()) === 'post') {
            $this->licensePost();
        }

        return $this->licenseGet();
    }

    /**
     * @return string[]
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function licenseGet(): array
    {
        $this->eeLicenseBanner->setFromLicenseResult(
            $this->licenseStatus->get(),
        );

        $model = $this->getLicenseAction->render();

        return [
            'heading' => $model->heading(),
            'body' => $model->content(),
        ];
    }

    /**
     * @throws GuzzleException
     */
    private function licensePost(): void
    {
        $this->postLicenseAction->run($this->request);
    }
}
