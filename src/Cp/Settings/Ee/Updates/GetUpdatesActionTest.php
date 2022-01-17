<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee\Updates;

use BuzzingPixel\Ansel\Cp\Settings\Ee\Sidebar;
use BuzzingPixel\Ansel\Shared\EeCssJs;
use BuzzingPixel\Ansel\UpdatesFeed\UpdateCollection;
use BuzzingPixel\Ansel\UpdatesFeed\UpdatesFeedRepository;
use EE_Lang;
use PHPUnit\Framework\TestCase;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function assert;
use function is_array;

class GetUpdatesActionTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private UpdateCollection $updateCollection;

    private GetUpdatesAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->updateCollection = new UpdateCollection();

        $this->action = new GetUpdatesAction(
            $this->mockLang(),
            $this->mockEeCssJs(),
            $this->mockSideBar(),
            $this->mockTwig(),
            $this->mockUpdatesFeedRepository(),
        );
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

    private function mockUpdatesFeedRepository(): UpdatesFeedRepository
    {
        $mock = $this->createMock(
            UpdatesFeedRepository::class,
        );

        $mock->method('getUpdates')->willReturn(
            $this->updateCollection,
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
            'updates-lang',
            $model->heading(),
        );

        self::assertSame(
            'fooBarTwigRender',
            $model->content(),
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
                'active' => 'updates',
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
            '@AnselSrc/Cp/Settings/Ee/Updates/Updates.twig',
            $call2['name'],
        );

        $context = $call2['context'];

        assert(is_array($context));

        self::assertCount(3, $context);

        self::assertSame(
            ['foo' => 'bar'],
            $context['sidebar'],
        );

        self::assertSame(
            'updates-lang',
            $context['pageTitle'],
        );

        self::assertSame(
            $this->updateCollection,
            $context['updates'],
        );
    }
}
