<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\Craft;

use craft\base\VolumeInterface;
use craft\services\Volumes;
use PHPUnit\Framework\TestCase;

class GetAllVolumesTest extends TestCase
{
    private GetAllVolumes $getAllVolumes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getAllVolumes = new GetAllVolumes(
            $this->mockVolumesService(),
        );
    }

    private function mockVolumesService(): Volumes
    {
        $volume1 = $this->createMock(VolumeInterface::class);
        $volume2 = $this->createMock(VolumeInterface::class);

        $volume1->name = 'vol1Name';
        $volume2->name = 'vol2Name';

        $volume1->uid = 'vol1Uid';
        $volume2->uid = 'vol2Uid';

        $mock = $this->createMock(Volumes::class);

        $mock->method('getAllVolumes')->willReturn([
            $volume1,
            $volume2,
        ]);

        return $mock;
    }

    public function testGet(): void
    {
        $collection = $this->getAllVolumes->get();

        self::assertSame(
            [
                [
                    'label' => 'Choose Location...',
                    'value' => '',
                ],
                [
                    'label' => 'vol1Name',
                    'value' => 'vol1Uid',
                ],
                [
                    'label' => 'vol2Name',
                    'value' => 'vol2Uid',
                ],
            ],
            $collection->asArray(),
        );
    }
}
