<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Craft\Index;

use BuzzingPixel\Ansel\Settings\SettingItem;
use BuzzingPixel\Ansel\Settings\SettingsCollection;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Shared\CraftRegisterAssetBundle;
use BuzzingPixel\Ansel\Translations\TranslatorForTesting;
use PHPUnit\Framework\TestCase;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\InvalidConfigException;

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
            $this->mockTwig(),
            new TranslatorForTesting(),
            $this->mockRegisterAssetBundle(),
            $this->mockSettingsRepository(),
        );
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

    private function mockRegisterAssetBundle(): CraftRegisterAssetBundle
    {
        $mock = $this->createMock(
            CraftRegisterAssetBundle::class,
        );

        $mock->method('register')->willReturnCallback(
            function (): void {
                $this->calls[] = [
                    'object' => 'CraftRegisterAssetBundle',
                    'method' => 'register',
                ];
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
     * @throws InvalidConfigException
     */
    public function testRender(): void
    {
        $model = $this->action->render();

        self::assertSame(
            'Ansel settings-translator',
            $model->title(),
        );

        self::assertSame(
            'fooBarTwigRender',
            $model->content(),
        );

        self::assertCount(2, $this->calls);

        $call0 = $this->calls[0];

        self::assertSame(
            [
                'object' => 'CraftRegisterAssetBundle',
                'method' => 'register',
            ],
            $call0,
        );

        $call1 = $this->calls[1];

        assert(is_array($call1));

        self::assertCount(4, $call1);

        self::assertSame(
            'TwigEnvironment',
            $call1['object'],
        );

        self::assertSame('render', $call1['method']);

        self::assertSame(
            '@AnselSrc/Cp/Settings/Craft/Index/Index.twig',
            $call1['name'],
        );

        $context = $call1['context'];

        assert(is_array($context));

        self::assertCount(1, $context);

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
