<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Cp\Settings\Ee;

use EE_Lang;
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
            $this->mockLang(),
            $this->mockUrlFactory(),
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
                        'content' => 'settings-lang',
                        'href' => '/url/object/addons/settings/ansel',
                        'isActive' => false,
                    ],
                'updates' =>
                    [
                        'content' => 'updates-lang',
                        'href' => '/url/object/addons/settings/ansel/updates',
                        'isActive' => true,
                    ],
                'license' =>
                    [
                        'content' => 'license-lang',
                        'href' => '/url/object/addons/settings/ansel/license',
                        'isActive' => false,
                    ],
            ],
            $value,
        );
    }
}
