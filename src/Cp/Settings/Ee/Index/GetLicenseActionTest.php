<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Index;

use BuzzingPixel\Ansel\Cp\Settings\Ee\License\GetLicenseAction;
use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use cebe\markdown\GithubMarkdown;
use Csrf;
use EE_Lang;
use ExpressionEngine\Library\CP\URL;
use ExpressionEngine\Service\URL\URLFactory;
use PHPUnit\Framework\TestCase;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetLicenseActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private GetLicenseAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->action = new GetLicenseAction(
            $this->mockCsrf(),
            $this->mockLang(),
            $this->mockEeCssJs(),
            $this->mockSideBar(),
            $this->mockTwig(),
            $this->mockUrlFactory(),
            $this->mockMarkDown(),
            $this->mockInternalFunctions(),
            $this->mockSettingsRepository(),
        );
    }

    private function mockCsrf(): Csrf
    {
        $mock = $this->createMock(Csrf::class);

        $mock->method('get_user_token')->willReturn(
            'foo-csrf-token',
        );

        return $mock;
    }

    private function mockLang(): EE_Lang
    {
        $mock = $this->createMock(EE_Lang::class);

        $mock->method('line')->willReturnCallback(
            static function (string $which): string {
                return $which . '-lang';
            }
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

    private function mockUrlFactory(): URLFactory
    {
        $mock = $this->createMock(URLFactory::class);

        $mock->method('make')->willReturnCallback(
            function (string $path): URL {
                return $this->mockUrl($path);
            }
        );

        return $mock;
    }

    private function mockUrl(string $path): URL
    {
        $mock = $this->createMock(URL::class);

        $mock->method('compile')->willReturn(
            '/url/object/' . $path,
        );

        return $mock;
    }

    private function mockMarkDown(): GithubMarkdown
    {
        $mock = $this->createMock(GithubMarkdown::class);

        $mock->method('parse')->willReturnCallback(
            function (string $text): string {
                $this->calls[] = [
                    'object' => 'GithubMarkdown',
                    'method' => 'parse',
                    'text' => $text,
                ];

                return 'foo-markdown';
            }
        );

        return $mock;
    }

    private function mockInternalFunctions(): InternalFunctions
    {
        $mock = $this->createMock(InternalFunctions::class);

        $mock->method('fileGetContents')->willReturnCallback(
            function (string $filename): string {
                $this->calls[] = [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'filename' => $filename,
                ];

                return 'foo-file-contents';
            }
        );

        return $mock;
    }

    private function mockSettingsRepository(): SettingsRepositoryContract
    {
        $mock = $this->createMock(
            SettingsRepositoryContract::class,
        );

        $mock->method('getSettings')->willReturnCallback(
            static function (): SettingsCollection {
                return new SettingsCollection([
                    new SettingItem(
                        'string',
                        'license_key',
                        'license_key',
                        'foo-license-key',
                        'license_key',
                        false,
                    ),
                ]);
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
        $model = $this->action->render();

        self::assertSame(
            'license-lang',
            $model->heading(),
        );

        self::assertSame(
            'fooBarTwigRender',
            $model->content(),
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
                    'active' => 'license',
                ],
                [
                    'object' => 'InternalFunctions',
                    'method' => 'fileGetContents',
                    'filename' => '/Volumes/Promenade/git/ansel/src/Cp/Settings/Ee/License/License.md',
                ],
                [
                    'object' => 'GithubMarkdown',
                    'method' => 'parse',
                    'text' => 'foo-file-contents',
                ],
                [
                    'object' => 'TwigEnvironment',
                    'method' => 'render',
                    'name' => '@AnselSrc/Cp/Settings/Ee/License/License.twig',
                    'context' => [
                        'sidebar' => ['foo' => 'bar'],
                        'pageTitle' => 'license-lang',
                        'formAction' => '/url/object/addons/settings/ansel/license',
                        'csrfToken' => 'foo-csrf-token',
                        'submitButtonContent' => 'update-lang',
                        'submitButtonWorkingContent' => 'updating-lang...',
                        'licenseLabel' => 'license_agreement-lang',
                        'licenseText' => 'foo-markdown',
                        'licenseKeyLabel' => 'your_license_key-lang',
                        'licenseKey' => 'foo-license-key',
                    ],
                ],
            ],
            $this->calls,
        );
    }
}
