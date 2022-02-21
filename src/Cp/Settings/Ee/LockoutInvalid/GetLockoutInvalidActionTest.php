<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\LockoutInvalid;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EE\EeCssJs;
use BuzzingPixel\Ansel\Shared\Meta\Meta;
use BuzzingPixel\Ansel\Translations\TranslatorForTesting;
use PHPUnit\Framework\TestCase;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLockoutInvalidActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private GetLockoutInvalidAction $getLockoutInvalidAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->getLockoutInvalidAction = new GetLockoutInvalidAction(
            $this->mockMeta(),
            $this->mockEeCssJs(),
            $this->mockSideBar(),
            $this->mockTwig(),
            new TranslatorForTesting(),
        );
    }

    private function mockMeta(): Meta
    {
        $mock = $this->createMock(Meta::class);

        $mock->method('softwarePageLink')->willReturn(
            '/software/page',
        );

        $mock->method('licenseCpLink')->willReturn(
            '/license/cp',
        );

        $mock->method('buzzingPixelAccountUrl')->willReturn(
            '/bzpxl/acct',
        );

        return $mock;
    }

    private function mockEeCssJs(): EeCssJs
    {
        $mock = $this->createMock(EeCssJs::class);

        $mock->method('add')->willReturnCallback(
            function (): void {
                $this->calls[] = [
                    'object' => 'EeCssJs',
                    'method' => 'add',
                ];
            }
        );

        return $mock;
    }

    private function mockSideBar(): Sidebar
    {
        $mock = $this->createMock(Sidebar::class);

        $mock->method('get')->willReturnCallback(
            function (string $active): array {
                $this->calls[] = [
                    'object' => 'Sidebar',
                    'method' => 'get',
                    'active' => $active,
                ];

                return ['foo' => 'bar'];
            }
        );

        return $mock;
    }

    private function mockTwig(): TwigEnvironment
    {
        $mock = $this->createMock(TwigEnvironment::class);

        $mock->method('render')->willReturnCallback(
            function (string $name, array $context): string {
                $this->calls[] = [
                    'object' => 'TwigEnvironment',
                    'method' => 'render',
                    'name' => $name,
                    'context' => $context,
                ];

                return 'fooBarTwigRender';
            }
        );

        return $mock;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testRender(): void
    {
        $model = $this->getLockoutInvalidAction->render();

        self::assertSame(
            [
                'heading' => 'ansel_license_invalid-translator',
                'body' => 'fooBarTwigRender',
            ],
            $model->toArray(),
        );

        self::assertSame(
            [
                [
                    'object' => 'EeCssJs',
                    'method' => 'add',
                ],
                [
                    'object' => 'Sidebar',
                    'method' => 'get',
                    'active' => '',
                ],
                [
                    'object' => 'TwigEnvironment',
                    'method' => 'render',
                    'name' => '@AnselSrc/Cp/Settings/Ee/LockoutInvalid/LockoutInvalid.twig',
                    'context' => [
                        'sidebar' => ['foo' => 'bar'],
                        'pageTitle' => 'ansel_license_invalid-translator',
                        'content' => 'ansel_license_invalid_body-translator <a href="/bzpxl/acct">link</a><a href="/software/page">link</a> <a href="/license/cp">link</a>',
                    ],
                ],
            ],
            $this->calls,
        );
    }
}
