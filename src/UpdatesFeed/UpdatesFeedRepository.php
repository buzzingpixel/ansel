<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\UpdatesFeed;

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Ansel\UpdatesFeed\FeedRetrieval\RetrieveFeedFactory;
use cebe\markdown\GithubMarkdown;
use DateTimeImmutable;
use DateTimeInterface;

use function array_map;
use function html_entity_decode;
use function implode;
use function json_decode;
use function str_replace;
use function strpos;
use function substr;
use function version_compare;

use const ENT_QUOTES;

class UpdatesFeedRepository
{
    private Meta $meta;

    private GithubMarkdown $markdown;

    private RetrieveFeedFactory $retrieveFeedFactory;

    public function __construct(
        Meta $meta,
        GithubMarkdown $markdown,
        RetrieveFeedFactory $retrieveFeedFactory
    ) {
        $this->meta                = $meta;
        $this->markdown            = $markdown;
        $this->retrieveFeedFactory = $retrieveFeedFactory;
    }

    public function getUpdates(): UpdateCollection
    {
        $json = (array) json_decode(
            $this->retrieveFeedFactory->get()->retrieve(),
            true,
        );

        // $json[0]['version'] = '3.0.1';

        $items = array_map(
        /** @phpstan-ignore-next-line */
            fn (array $item) => new UpdateItem(
                version_compare(
                    $item['version'],
                    $this->meta->version(),
                    '>'
                ),
                $item['version'],
                $item['downloadUrl'],
                /** @phpstan-ignore-next-line */
                DateTimeImmutable::createFromFormat(
                    DateTimeInterface::ISO8601,
                    $item['date'],
                ),
                $this->parseNotes($item['notes']),
            ),
            $json,
        );

        return new UpdateCollection($items);
    }

    /**
     * @param string[] $notes
     */
    private function parseNotes(array $notes): string
    {
        $itemsToRemove = [];

        foreach ($notes as $note) {
            if (strpos($note, '#') !== 0) {
                continue;
            }

            $itemsToRemove[] = '[' . substr($note, 2) . '] ';
        }

        $mdString = html_entity_decode(
            implode("\n\n", $notes),
            ENT_QUOTES
        );

        foreach ($itemsToRemove as $item) {
            $mdString = str_replace(
                $item,
                '',
                $mdString,
            );
        }

        return $this->markdown->parse($mdString);
    }
}
