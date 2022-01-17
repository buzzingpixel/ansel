<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed;

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval\RetrieveFeedContract;
use BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval\RetrieveFeedFactory;
use cebe\markdown\GithubMarkdown;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

class UpdatesFeedRepositoryTest extends TestCase
{
    private UpdatesFeedRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new UpdatesFeedRepository(
            new Meta('2.0.0'),
            new GithubMarkdown(),
            $this->mockRetrieveFeedFactory(),
        );
    }

    private function mockRetrieveFeedFactory(): RetrieveFeedFactory
    {
        $mock = $this->createMock(
            RetrieveFeedFactory::class,
        );

        $mock->method('get')->willReturn(
            $this->mockRetrieveFeedContract(),
        );

        return $mock;
    }

    private function mockRetrieveFeedContract(): RetrieveFeedContract
    {
        $mock = $this->createMock(
            RetrieveFeedContract::class,
        );

        $mock->method('retrieve')->willReturn(
            file_get_contents(
                __DIR__ . '/UpdatesFeedForTesting.json',
            ),
        );

        return $mock;
    }

    public function testGetUpdates(): void
    {
        $collection = $this->repository->getUpdates();

        self::assertSame(4, $collection->count());

        $first       = $collection->first();
        $firstOrNull = $collection->firstOrNull();
        self::assertSame($first, $firstOrNull);

        $at1       = $collection->at(1);
        $at1OrNull = $collection->atOrNull(1);
        self::assertSame($at1, $at1OrNull);

        $at2       = $collection->at(2);
        $at2OrNull = $collection->atOrNull(2);
        self::assertSame($at2, $at2OrNull);

        $at3       = $collection->at(3);
        $at3OrNull = $collection->atOrNull(3);
        self::assertSame($at3, $at3OrNull);

        self::assertNull($collection->atOrNull(4));

        $emptyCollection = $collection->filter(
            static fn (UpdateItem $i) => $i->version() === '123',
        );

        self::assertSame(0, $emptyCollection->count());

        self::assertNull($emptyCollection->firstOrNull());

        self::assertSame(
            [
                [
                    'isNew' => true,
                    'version' => '2.2.2',
                    'downloadUrl' => 'https://buzzingpixel.com/software/ansel-ee/download',
                    'date' => '2022-01-07T00:36:00+00:00',
                    'notes' => "<h1>Fixed</h1>\n<p>Foo Bar Fixed</p>\n",
                ],
                [
                    'isNew' => true,
                    'version' => '2.2.1',
                    'downloadUrl' => 'https://buzzingpixel.com/software/ansel-ee/download',
                    'date' => '2021-11-05T15:00:00+00:00',
                    'notes' => "<h1>Fixed</h1>\n<p>Bar Baz</p>\n",
                ],
                [
                    'isNew' => true,
                    'version' => '2.2.0',
                    'downloadUrl' => 'https://buzzingpixel.com/software/ansel-ee/download',
                    'date' => '2020-12-19T21:14:00+00:00',
                    'notes' => "<h1>Added</h1>\n<p>Foo</p>\n<h1>Fixed</h1>\n<p>Bar</p>\n",
                ],
                [
                    'isNew' => true,
                    'version' => '2.1.5',
                    'downloadUrl' => 'https://buzzingpixel.com/software/ansel-ee/download',
                    'date' => '2018-05-12T20:13:00+00:00',
                    'notes' => "<h1>Fixed</h1>\n<p>Foo</p>\n",
                ],
            ],
            $collection->map(
                static fn (UpdateItem $i) => [
                    'isNew' => $i->isNew(),
                    'version' => $i->version(),
                    'downloadUrl' => $i->downloadUrl(),
                    'date' => $i->date()->format(
                        DateTimeInterface::ATOM,
                    ),
                    'notes' => $i->notes(),
                ],
            ),
        );
    }
}
