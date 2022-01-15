<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use craft\web\View;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

class CraftRegisterAssetBundleTest extends TestCase
{
    /** @var mixed[] */
    private array $calls = [];

    private CraftRegisterAssetBundle $registerAssetBundle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];

        $this->registerAssetBundle = new CraftRegisterAssetBundle(
            $this->mockView(),
        );
    }

    private function mockView(): View
    {
        $mock = $this->createMock(View::class);

        $mock->method('registerAssetBundle')->willReturnCallback(
            function (string $name): void {
                $this->calls[] = [
                    'object' => 'View',
                    'method' => 'registerAssetBundle',
                    'name' => $name,
                ];
            }
        );

        return $mock;
    }

    /**
     * @throws InvalidConfigException
     */
    public function testRegister(): void
    {
        $this->registerAssetBundle->register();

        self::assertSame(
            [
                [
                    'object' => 'View',
                    'method' => 'registerAssetBundle',
                    'name' => CraftAnselAssetBundle::class,
                ],
            ],
            $this->calls,
        );
    }
}
