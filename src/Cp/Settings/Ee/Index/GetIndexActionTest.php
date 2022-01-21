<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Index;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\Translations\TranslatorForTesting;
use Csrf;
use ExpressionEngine\Library\CP\URL;
use ExpressionEngine\Service\URL\URLFactory;
use PHPUnit\Framework\TestCase;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function assert;
use function is_array;

class GetIndexActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private GetIndexAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->action = new GetIndexAction(
            $this->mockCsrf(),
            $this->mockEeCssJs(),
            $this->mockSideBar(),
            $this->mockTwig(),
            $this->mockUrlFactory(),
            new TranslatorForTesting(),
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
                        'foo-bar-key-1',
                        'foo-bar-label-1',
                        'foo-bar-value-1',
                        'foo-bar-desc-1',
                        false,
                    ),
                    new SettingItem(
                        'string',
                        'foo-bar-key-2',
                        'foo-bar-label-2',
                        'foo-bar-value-2',
                        'foo-bar-desc-2',
                        true,
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
            'settings-translator',
            $model->heading(),
        );

        self::assertSame(
            'fooBarTwigRender',
            $model->body(),
        );

        self::assertSame(
            [
                'heading' => 'settings-translator',
                'content' => 'fooBarTwigRender',
            ],
            $model->toArray(),
        );

        self::assertCount(3, $this->calls);

        $call0 = $this->calls[0];

        self::assertSame(
            [
                'object' => 'EeCssJs',
                'method' => 'add',
            ],
            $call0,
        );

        $call1 = $this->calls[1];

        self::assertSame(
            [
                'object' => 'Sidebar',
                'method' => 'get',
                'active' => 'settings',
            ],
            $call1,
        );

        $call2 = $this->calls[2];

        assert(is_array($call2));

        self::assertCount(4, $call2);

        self::assertSame(
            'TwigEnvironment',
            $call2['object'],
        );

        self::assertSame('render', $call2['method']);

        self::assertSame(
            '@AnselSrc/Cp/Settings/Ee/Index/Index.twig',
            $call2['name'],
        );

        $context = $call2['context'];

        assert(is_array($context));

        self::assertCount(7, $context);

        self::assertSame(
            ['foo' => 'bar'],
            $context['sidebar'],
        );

        self::assertSame(
            'settings-translator',
            $context['pageTitle'],
        );

        self::assertSame(
            '/url/object/addons/settings/ansel',
            $context['formAction'],
        );

        self::assertSame(
            'foo-csrf-token',
            $context['csrfToken'],
        );

        self::assertSame(
            'save_settings-translator',
            $context['submitButtonContent'],
        );

        self::assertSame(
            'saving-translator...',
            $context['submitButtonWorkingContent'],
        );

        $contextSettingsCollection = $context['settingsCollection'];

        assert(
            $contextSettingsCollection instanceof SettingsCollection
        );

        self::assertSame(
            [
                [
                    'type' => 'string',
                    'key' => 'foo-bar-key-2',
                    'label' => 'foo-bar-label-2',
                    'value' => 'foo-bar-value-2',
                    'description' => 'foo-bar-desc-2',
                    'includeOnSettingsPage' => true,
                ],
            ],
            $contextSettingsCollection->map(
                static fn (SettingItem $i) => [
                    'type' => $i->type(),
                    'key' => $i->key(),
                    'label' => $i->label(),
                    'value' => $i->value(),
                    'description' => $i->description(),
                    'includeOnSettingsPage' => $i->includeOnSettingsPage(),
                ],
            ),
        );
    }
}
