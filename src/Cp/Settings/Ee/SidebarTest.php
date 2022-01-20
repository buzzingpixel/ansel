<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee;

use BuzzingPixel\Ansel\Translations\TranslatorForTesting;
use ExpressionEngine\Library\CP\URL;
use ExpressionEngine\Service\URL\URLFactory;
use PHPUnit\Framework\TestCase;

class SidebarTest extends TestCase
{
    private Sidebar $sidebar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sidebar = new Sidebar(
            $this->mockUrlFactory(),
            new TranslatorForTesting(),
        );
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

    public function testGet(): void
    {
        $value = $this->sidebar->get('updates');

        self::assertSame(
            [
                'settings' =>
                    [
                        'content' => 'settings-translator',
                        'href' => '/url/object/addons/settings/ansel',
                        'isActive' => false,
                    ],
                'updates' =>
                    [
                        'content' => 'updates-translator',
                        'href' => '/url/object/addons/settings/ansel/updates',
                        'isActive' => true,
                    ],
                'license' =>
                    [
                        'content' => 'license-translator',
                        'href' => '/url/object/addons/settings/ansel/license',
                        'isActive' => false,
                    ],
            ],
            $value,
        );
    }
}
